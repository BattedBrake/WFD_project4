<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_doctor(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->postJson('/api/v1/dokter', [
            'name' => 'dr. Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
            'specialization' => 'Dokter Umum',
            'description' => 'Dokter konsultasi umum.',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'dr. Budi Santoso',
            'email' => 'budi@example.com',
            'role' => User::ROLE_DOKTER,
        ]);

        $this->assertDatabaseHas('doctors', [
            'specialization' => 'Dokter Umum',
            'description' => 'Dokter konsultasi umum.',
        ]);
    }

    public function test_admin_can_create_schedule(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/jadwal', [
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'quota' => 5,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('schedules', [
            'doctor_id' => $doctor->id,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'quota' => 5,
        ]);
    }

    public function test_admin_cannot_create_overlapping_schedule_for_same_doctor(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $doctor = Doctor::factory()->create();
        $date = now()->addDay()->toDateString();

        Schedule::factory()->create([
            'doctor_id' => $doctor->id,
            'date' => $date,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'quota' => 5,
        ]);

        $response = $this->actingAs($admin)->postJson('/api/v1/jadwal', [
            'doctor_id' => $doctor->id,
            'date' => $date,
            'start_time' => '09:30',
            'end_time' => '10:30',
            'quota' => 5,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('start_time');
    }

    public function test_doctor_can_create_own_schedule(): void
    {
        $doctorUser = User::factory()->create(['role' => User::ROLE_DOKTER]);
        $doctor = Doctor::factory()->create(['user_id' => $doctorUser->id]);

        $response = $this->actingAs($doctorUser)->postJson('/api/v1/jadwal', [
            'date' => now()->addDay()->toDateString(),
            'start_time' => '13:00',
            'end_time' => '14:00',
            'quota' => 3,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('schedules', [
            'doctor_id' => $doctor->id,
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'quota' => 3,
        ]);
    }

    public function test_patient_cannot_access_admin_crud(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);

        $this->actingAs($patient)
            ->postJson('/api/v1/dokter', [
                'name' => 'dr. Tidak Boleh',
                'email' => 'blocked@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'specialization' => 'Umum',
            ])
            ->assertForbidden();
    }

    public function test_schedule_with_reservations_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $schedule = Schedule::factory()->create();

        Reservation::query()->create([
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Kontrol rutin',
            'status' => Reservation::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->deleteJson("/api/v1/jadwal/{$schedule->id}");

        $response->assertUnprocessable();

        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
        ]);
    }

    public function test_patient_can_create_reservation(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $schedule = Schedule::factory()->create(['quota' => 2]);

        $response = $this->actingAs($patient)->postJson('/api/v1/reservasi', [
            'schedule_id' => $schedule->id,
            'complaint' => 'Sakit kepala',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('reservations', [
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Sakit kepala',
            'status' => Reservation::STATUS_PENDING,
        ]);
    }

    public function test_patient_cancels_own_reservation_without_deleting_it(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $schedule = Schedule::factory()->create();
        $reservation = Reservation::query()->create([
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Batalkan jadwal',
            'status' => Reservation::STATUS_PENDING,
        ]);

        $response = $this->actingAs($patient)->deleteJson("/api/v1/reservasi/{$reservation->id}");

        $response->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => Reservation::STATUS_CANCELLED,
        ]);
    }

    public function test_doctor_can_update_status_for_own_reservation(): void
    {
        $doctorUser = User::factory()->create(['role' => User::ROLE_DOKTER]);
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $doctor = Doctor::factory()->create(['user_id' => $doctorUser->id]);
        $schedule = Schedule::factory()->create(['doctor_id' => $doctor->id]);
        $reservation = Reservation::query()->create([
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Kontrol',
            'status' => Reservation::STATUS_PENDING,
        ]);

        $response = $this->actingAs($doctorUser)->patchJson("/api/v1/reservasi/{$reservation->id}", [
            'status' => Reservation::STATUS_HOLD,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => Reservation::STATUS_HOLD,
        ]);
    }

    public function test_patient_cannot_update_another_patient_reservation(): void
    {
        $patient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $otherPatient = User::factory()->create(['role' => User::ROLE_PASIEN]);
        $schedule = Schedule::factory()->create();
        $reservation = Reservation::query()->create([
            'user_id' => $otherPatient->id,
            'schedule_id' => $schedule->id,
            'complaint' => 'Kontrol',
            'status' => Reservation::STATUS_PENDING,
        ]);

        $this->actingAs($patient)
            ->patchJson("/api/v1/reservasi/{$reservation->id}", [
                'complaint' => 'Tidak boleh',
            ])
            ->assertForbidden();
    }
}
