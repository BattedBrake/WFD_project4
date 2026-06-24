<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_DOKTER => redirect()->route('dokter.dashboard'),
            default => redirect()->route('pasien.dashboard'),
        };
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function dokter(): View
    {
        $user = Auth::user();
        // Ambil ID dokter yang terikat dengan user login
        $doctorId = $user->doctor?->id;

        // 1. Hitung jumlah reservasi yang statusnya 'pending' khusus dokter ini
        $jumlahPending = Reservation::whereHas('schedule', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->where('status', 'pending')->count();

        // 2. Hitung total seluruh jadwal praktik yang pernah dibuat oleh dokter ini
        $totalJadwal = Schedule::where('doctor_id', $doctorId)->count();

        // 3. Hitung jumlah reservasi yang statusnya sudah 'done' khusus dokter ini
        $pasienSelesai = Reservation::whereHas('schedule', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->where('status', 'done')->count();

        return view('dokter.doctor_dashboard', compact('jumlahPending', 'totalJadwal', 'pasienSelesai'));
    }

    public function pasien(): View
    {
        $pasienId = Auth::id();

        // Hitung statistik riil berdasarkan id pasien yang sedang login
        $jumlahMenunggu = Reservation::where('user_id', $pasienId)->where('status', 'pending')->count();
        $jumlahDisetujui = Reservation::where('user_id', $pasienId)->where('status', 'hold')->count();
        $totalRiwayat = Reservation::where('user_id', $pasienId)->where('status', 'done')->count();

        return view('pasien.pasien', compact('jumlahMenunggu', 'jumlahDisetujui', 'totalRiwayat'));
    }

    public function doctorSchedule(): View
    {
        $doctorId = Auth::user()->doctor?->id;

        $schedules = Schedule::query()
            ->withCount([
                'reservations as active_reservations_count' => fn ($query) => $query->whereIn('status', [
                    Reservation::STATUS_PENDING,
                    Reservation::STATUS_HOLD,
                ]),
            ])
            ->where('doctor_id', $doctorId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('dokter.doctor_schedule', compact('schedules'));
    }

    public function doctorCreateSchedule(): View
    {
        return view('dokter.doctor_create_schedule');
    }

    public function doctorStoreSchedule(Request $request): RedirectResponse
    {
        $doctorId = Auth::user()->doctor?->id;

        abort_unless($doctorId, 403, 'Profil dokter belum tersedia.');

        $validated = $request->validate([
            'day_of_week' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'quota' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $dayMap = [
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY,
            'minggu' => Carbon::SUNDAY,
        ];

        $date = now()->next($dayMap[$validated['day_of_week']])->toDateString();

        Schedule::query()->create([
            'doctor_id' => $doctorId,
            'date' => $date,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'quota' => $validated['quota'],
        ]);

        return redirect()->route('dokter.schedules')->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }
}
