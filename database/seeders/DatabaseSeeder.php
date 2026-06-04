<?php

namespace Database\Seeders;

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
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
        ]);

        User::query()->firstOrCreate([
            'email' => 'dokter@example.com',
        ], [
            'name' => 'Dokter Demo',
            'password' => 'password',
            'role' => User::ROLE_DOKTER,
        ]);

        User::query()->firstOrCreate([
            'email' => 'pasien@example.com',
        ], [
            'name' => 'Pasien Demo',
            'password' => 'password',
            'role' => User::ROLE_PASIEN,
        ]);
    }
}
