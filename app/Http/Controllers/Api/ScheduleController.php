<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $schedules = Schedule::query()
            ->with('doctor.user:id,name,email,phone,role')
            ->orderByDesc('date')
            ->orderBy('start_time')
            ->get();

        return $this->success($schedules, 'Data jadwal berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $schedule = Schedule::query()->create($this->validateSchedule($request));

        return $this->success(
            $schedule->load('doctor.user:id,name,email,phone,role'),
            'Jadwal berhasil ditambahkan',
            201
        );
    }

    public function show(Schedule $jadwal): JsonResponse
    {
        return $this->success(
            $jadwal->load('doctor.user:id,name,email,phone,role'),
            'Detail jadwal berhasil diambil'
        );
    }

    public function update(Request $request, Schedule $jadwal): JsonResponse
    {
        $jadwal->update($this->validateSchedule($request, $jadwal));

        return $this->success(
            $jadwal->fresh()->load('doctor.user:id,name,email,phone,role'),
            'Jadwal berhasil diperbarui'
        );
    }

    public function destroy(Schedule $jadwal): JsonResponse
    {
        if ($jadwal->reservations()->exists()) {
            return $this->error('Jadwal tidak bisa dihapus karena sudah memiliki reservasi.', 422);
        }

        $jadwal->delete();

        return $this->success(null, 'Jadwal berhasil dihapus');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateSchedule(Request $request, ?Schedule $currentSchedule = null): array
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'quota' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $validated['start_time'] = $this->normalizeTime($validated['start_time']);
        $validated['end_time'] = $this->normalizeTime($validated['end_time']);

        $hasConflict = Schedule::query()
            ->where('doctor_id', $validated['doctor_id'])
            ->whereDate('date', $validated['date'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->when($currentSchedule, fn ($query) => $query->where('id', '!=', $currentSchedule->id))
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'start_time' => 'Jadwal dokter bentrok dengan jadwal lain pada tanggal tersebut.',
            ]);
        }

        return $validated;
    }

    private function normalizeTime(string $time): string
    {
        return strlen($time) === 5 ? "{$time}:00" : $time;
    }
}
