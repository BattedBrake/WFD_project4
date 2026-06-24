<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole(User::ROLE_DOKTER)) {
            $reservations = Reservation::query()
                ->whereHas('schedule', fn ($query) => $query->where('doctor_id', $user->doctor?->id))
                ->with(['schedule.doctor.user', 'user'])
                ->latest()
                ->get();

            return view('dokter.doctor_change_reservation', [
                'reservations' => $reservations,
                'isDoctor' => true,
            ]);
        }

        $reservations = Reservation::query()
            ->where('user_id', $user->id)
            ->with(['schedule.doctor.user'])
            ->latest()
            ->get();

        return view('pasien.index', [
            'reservations' => $reservations,
            'isDoctor' => false,
        ]);
    }

    public function create(): View
    {
        $schedules = Schedule::query()
            ->with(['doctor.user'])
            ->whereDate('date', '>=', now()->toDateString())
            ->get()
            ->filter(fn (Schedule $schedule): bool => $schedule->hasAvailableQuota())
            ->values();

        return view('pasien.create', compact('schedules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'complaint' => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule = Schedule::query()->findOrFail($validated['schedule_id']);

        if (! $schedule->hasAvailableQuota()) {
            return back()->withErrors([
                'schedule_id' => 'Jadwal ini sudah penuh. Silakan pilih jadwal lain.',
            ])->withInput();
        }

        Reservation::query()->create([
            'user_id' => $request->user()->id,
            'schedule_id' => $schedule->id,
            'complaint' => $validated['complaint'] ?? null,
            'status' => Reservation::STATUS_PENDING,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat. Status saat ini: Pending.');
    }

    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->user_id === $request->user()->id, 403);
        abort_unless($reservation->canBeCancelled(), 403);

        $reservation->update(['status' => Reservation::STATUS_CANCELLED]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        abort_unless($request->user()->hasRole(User::ROLE_DOKTER), 403);
        abort_unless($reservation->schedule?->doctor?->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,hold,done,cancelled'],
        ]);

        $reservation->update(['status' => $validated['status']]);

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
