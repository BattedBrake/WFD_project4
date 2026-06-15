<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    use ApiResponse;

    private const ACTIVE_STATUSES = [
        Reservation::STATUS_PENDING,
        Reservation::STATUS_HOLD,
    ];

    private const ALL_STATUSES = [
        Reservation::STATUS_PENDING,
        Reservation::STATUS_HOLD,
        Reservation::STATUS_DONE,
        Reservation::STATUS_CANCELLED,
    ];

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $reservations = Reservation::query()
            ->with(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role'])
            ->when($user->hasRole(User::ROLE_PASIEN), fn ($query) => $query->where('user_id', $user->id))
            ->when($user->hasRole(User::ROLE_DOKTER), function ($query) use ($user): void {
                $query->whereHas('schedule.doctor', fn ($doctorQuery) => $doctorQuery->where('user_id', $user->id));
            })
            ->latest()
            ->get();

        return $this->success($reservations, 'Data reservasi berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user->hasRole([User::ROLE_ADMIN, User::ROLE_PASIEN]), 403);

        $rules = [
            'schedule_id' => ['required', 'exists:schedules,id'],
            'complaint' => ['nullable', 'string', 'max:1000'],
        ];

        if ($user->hasRole(User::ROLE_ADMIN)) {
            $rules['user_id'] = ['required', 'exists:users,id'];
            $rules['status'] = ['nullable', Rule::in(self::ALL_STATUSES)];
        }

        $validated = $request->validate($rules);
        $patientId = $user->hasRole(User::ROLE_ADMIN) ? (int) $validated['user_id'] : $user->id;
        $status = $validated['status'] ?? Reservation::STATUS_PENDING;
        $schedule = Schedule::query()->findOrFail($validated['schedule_id']);

        $this->ensurePatient($patientId);
        $this->ensureScheduleHasQuota($schedule, $status);

        $reservation = Reservation::query()->create([
            'user_id' => $patientId,
            'schedule_id' => $schedule->id,
            'complaint' => $validated['complaint'] ?? null,
            'status' => $status,
        ]);

        return $this->success(
            $reservation->load(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role']),
            'Reservasi berhasil ditambahkan',
            201
        );
    }

    public function show(Request $request, Reservation $reservasi): JsonResponse
    {
        abort_unless($this->canAccessReservation($request->user(), $reservasi), 403);

        return $this->success(
            $reservasi->load(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role']),
            'Detail reservasi berhasil diambil'
        );
    }

    public function update(Request $request, Reservation $reservasi): JsonResponse
    {
        $user = $request->user();

        abort_unless($this->canAccessReservation($user, $reservasi), 403);

        if ($user->hasRole(User::ROLE_ADMIN)) {
            $validated = $request->validate([
                'user_id' => ['sometimes', 'required', 'exists:users,id'],
                'schedule_id' => ['sometimes', 'required', 'exists:schedules,id'],
                'complaint' => ['sometimes', 'nullable', 'string', 'max:1000'],
                'status' => ['sometimes', 'required', Rule::in(self::ALL_STATUSES)],
            ]);

            if (isset($validated['user_id'])) {
                $this->ensurePatient((int) $validated['user_id']);
            }

            $this->ensureUpdateKeepsQuota($reservasi, $validated);
            $reservasi->update($validated);

            return $this->success(
                $reservasi->fresh()->load(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role']),
                'Reservasi berhasil diperbarui'
            );
        }

        if ($user->hasRole(User::ROLE_DOKTER)) {
            abort_unless($reservasi->schedule?->doctor?->user_id === $user->id, 403);

            $validated = $request->validate([
                'status' => ['required', Rule::in(self::ALL_STATUSES)],
            ]);

            $this->ensureUpdateKeepsQuota($reservasi, $validated);
            $reservasi->update($validated);

            return $this->success(
                $reservasi->fresh()->load(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role']),
                'Status reservasi berhasil diperbarui'
            );
        }

        abort_unless($reservasi->user_id === $user->id && $reservasi->status === Reservation::STATUS_PENDING, 403);

        $validated = $request->validate([
            'schedule_id' => ['sometimes', 'required', 'exists:schedules,id'],
            'complaint' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $this->ensureUpdateKeepsQuota($reservasi, $validated);
        $reservasi->update($validated);

        return $this->success(
            $reservasi->fresh()->load(['user:id,name,email,phone,role', 'schedule.doctor.user:id,name,email,phone,role']),
            'Reservasi berhasil diperbarui'
        );
    }

    public function destroy(Request $request, Reservation $reservasi): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole(User::ROLE_ADMIN)) {
            $reservasi->delete();

            return $this->success(null, 'Reservasi berhasil dihapus');
        }

        abort_unless($user->hasRole(User::ROLE_PASIEN), 403);
        abort_unless($reservasi->user_id === $user->id && $reservasi->canBeCancelled(), 403);

        $reservasi->delete();

        return $this->success(null, 'Reservasi berhasil dihapus');
    }

    private function canAccessReservation(User $user, Reservation $reservation): bool
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }

        if ($user->hasRole(User::ROLE_PASIEN)) {
            return $reservation->user_id === $user->id;
        }

        return $reservation->schedule?->doctor?->user_id === $user->id;
    }

    private function ensurePatient(int $userId): void
    {
        $isPatient = User::query()
            ->whereKey($userId)
            ->where('role', User::ROLE_PASIEN)
            ->exists();

        if (! $isPatient) {
            throw ValidationException::withMessages([
                'user_id' => 'Reservasi hanya bisa dibuat untuk user pasien.',
            ]);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function ensureUpdateKeepsQuota(Reservation $reservation, array $data): void
    {
        $scheduleId = $data['schedule_id'] ?? $reservation->schedule_id;
        $status = $data['status'] ?? $reservation->status;
        $schedule = Schedule::query()->findOrFail($scheduleId);

        $this->ensureScheduleHasQuota($schedule, $status, $reservation);
    }

    private function ensureScheduleHasQuota(Schedule $schedule, string $status, ?Reservation $ignoredReservation = null): void
    {
        if (! in_array($status, self::ACTIVE_STATUSES, true)) {
            return;
        }

        $activeReservations = $schedule->reservations()
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->when($ignoredReservation, fn ($query) => $query->where('id', '!=', $ignoredReservation->id))
            ->count();

        if ($activeReservations >= $schedule->quota) {
            throw ValidationException::withMessages([
                'schedule_id' => 'Jadwal ini sudah penuh. Silakan pilih jadwal lain.',
            ]);
        }
    }
}
