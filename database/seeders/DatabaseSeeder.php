<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'phone' => '081200000001',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
        ]);

        $doctorUser = User::query()->firstOrCreate([
            'email' => 'dokter@example.com',
        ], [
            'name' => 'dr. Andi Pratama',
            'phone' => '081200000002',
            'password' => 'password',
            'role' => User::ROLE_DOKTER,
        ]);

        $doctor = Doctor::query()->firstOrCreate([
            'user_id' => $doctorUser->id,
        ], [
            'specialization' => 'Dokter Umum',
            'description' => 'Menangani konsultasi kesehatan umum dan pemeriksaan awal.',
        ]);

        $patient = User::query()->firstOrCreate([
            'email' => 'pasien@example.com',
        ], [
            'name' => 'Pasien Demo',
            'phone' => '081200000005',
            'password' => 'password',
            'role' => User::ROLE_PASIEN,
        ]);

        $schedule = Schedule::query()->firstOrCreate([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
        ], [
            'end_time' => '10:00',
            'quota' => 5,
        ]);

        Reservation::query()->firstOrCreate([
            'user_id' => $patient->id,
            'schedule_id' => $schedule->id,
        ], [
            'complaint' => 'Demam dan sakit kepala sejak kemarin.',
            'status' => 'pending',
        ]);
    }
}
