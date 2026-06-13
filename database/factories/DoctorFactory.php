<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => User::ROLE_DOKTER])->id,
            'specialization' => fake()->randomElement(['Umum', 'Gigi', 'Mata', 'Anak']),
            'description' => fake()->sentence(),
            'photo' => null,
        ];
    }
}
