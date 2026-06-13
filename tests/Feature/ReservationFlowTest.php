<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_create_a_pending_reservation_for_an_available_schedule(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $doctorUser = User::factory()->create(['role' => User::ROLE_DOKTER]);

        $doctor = Doctor::factory()->create([
            'user_id' => $doctorUser->id,
            'specialization' => 'Umum',
        ]);

        $schedule = Schedule::factory()->create([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'quota' => 1,
        ]);

        $response = $this->actingAs($patient)
            ->post(route('reservations.store'), [
                'schedule_id' => $schedule->id,
                'complaint' => 'Sakit kepala dan demam',
            ]);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Sakit kepala dan demam',
            'status' => Reservation::STATUS_PENDING,
        ]);
    }

    public function test_patient_cannot_book_when_schedule_quota_is_full(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $doctorUser = User::factory()->create(['role' => User::ROLE_DOKTER]);

        $doctor = Doctor::factory()->create([
            'user_id' => $doctorUser->id,
            'specialization' => 'Gigi',
        ]);

        $schedule = Schedule::factory()->create([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'quota' => 1,
        ]);

        Reservation::create([
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Sudah terisi',
            'status' => Reservation::STATUS_PENDING,
        ]);

        $secondPatient = User::factory()->create(['role' => User::ROLE_PASIEN]);

        $response = $this->actingAs($secondPatient)
            ->post(route('reservations.store'), [
                'schedule_id' => $schedule->id,
                'complaint' => 'Coba lagi',
            ]);

        $response->assertSessionHasErrors('schedule_id');
        $this->assertDatabaseCount('reservations', 1);
    }
}
