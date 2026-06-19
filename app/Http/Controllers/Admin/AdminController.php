<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalDokter = Doctor::count();
        $totalPasien = User::where('role', User::ROLE_PASIEN)->count();
        $reservasiHariIni = Reservation::whereHas('schedule', fn ($query) => $query->whereDate('date', today()))->count();
        $reservasiPending = Reservation::where('status', 'pending')->count();
        $reservasiTerbaru = Reservation::with(['user', 'schedule.doctor.user'])->latest()->take(10)->get();
        $dokterAktif = Doctor::with('user')->take(6)->get();

        return view('admin.dashboard', compact(
            'totalDokter',
            'totalPasien',
            'reservasiHariIni',
            'reservasiPending',
            'reservasiTerbaru',
            'dokterAktif',
        ));
    }

    public function dokterIndex(Request $request): View
    {
        $query = Doctor::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', fn ($user) => $user->where('name', 'like', '%'.$request->search.'%'));
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        $dokters = $query->latest()->paginate(10);
        $spesialisasiList = Doctor::distinct()->pluck('specialization');

        return view('admin.dokter.index', compact('dokters', 'spesialisasiList'));
    }

    public function dokterCreate(): View
    {
        return view('admin.dokter.form');
    }

    public function dokterStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialization' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'role' => User::ROLE_DOKTER,
        ]);

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('dokter', 'public')
            : null;

        Doctor::create([
            'user_id' => $user->id,
            'specialization' => $validated['specialization'],
            'description' => $validated['description'] ?? null,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function dokterShow(int $id): RedirectResponse
    {
        return redirect()->route('admin.dokter.edit', $id);
    }

    public function dokterEdit(int $id): View
    {
        $dokter = Doctor::with('user')->findOrFail($id);

        return view('admin.dokter.form', compact('dokter'));
    }

    public function dokterUpdate(Request $request, int $id): RedirectResponse
    {
        $dokter = Doctor::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($dokter->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialization' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $dokter->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($request->hasFile('photo')) {
            if ($dokter->photo) {
                Storage::disk('public')->delete($dokter->photo);
            }

            $dokter->photo = $request->file('photo')->store('dokter', 'public');
        }

        $dokter->specialization = $validated['specialization'];
        $dokter->description = $validated['description'] ?? null;
        $dokter->save();

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function dokterDestroy(int $id): RedirectResponse
    {
        $dokter = Doctor::with('user')->findOrFail($id);

        if ($dokter->photo) {
            Storage::disk('public')->delete($dokter->photo);
        }

        $dokter->user->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil dihapus.');
    }

    public function jadwalIndex(Request $request): View
    {
        $query = Schedule::with(['doctor.user', 'reservations']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $jadwals = $query->orderByDesc('date')->paginate(10);
        $dokters = Doctor::with('user')->get();

        return view('admin.jadwal.index', compact('jadwals', 'dokters'));
    }

    public function jadwalCreate(): View
    {
        $dokters = Doctor::with('user')->get();

        return view('admin.jadwal.form', compact('dokters'));
    }

    public function jadwalStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'quota' => ['required', 'integer', 'min:1'],
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function jadwalEdit(int $id): View
    {
        $jadwal = Schedule::findOrFail($id);
        $dokters = Doctor::with('user')->get();

        return view('admin.jadwal.form', compact('jadwal', 'dokters'));
    }

    public function jadwalUpdate(Request $request, int $id): RedirectResponse
    {
        $jadwal = Schedule::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'quota' => ['required', 'integer', 'min:1'],
        ]);

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function jadwalDestroy(int $id): RedirectResponse
    {
        Schedule::findOrFail($id)->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function reservasiIndex(Request $request): View
    {
        $query = Reservation::with(['user', 'schedule.doctor.user']);

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request): void {
                $query->whereHas('user', fn ($user) => $user->where('name', 'like', '%'.$request->search.'%'))
                    ->orWhereHas('schedule.doctor.user', fn ($user) => $user->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereHas('schedule', fn ($schedule) => $schedule->whereDate('date', $request->date));
        }

        $reservasis = $query->latest()->paginate(15);

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function reservasiShow(int $id): RedirectResponse
    {
        return redirect()->route('admin.reservasi.index', ['highlight' => $id]);
    }

    public function reservasiDestroy(int $id): RedirectResponse
    {
        Reservation::findOrFail($id)->delete();

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi berhasil dihapus.');
    }

    public function userIndex(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request): void {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    public function userEdit(int $id): View
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    public function userUpdate(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(User::ROLES)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function userDestroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}
