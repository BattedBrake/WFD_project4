<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class DoctorController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $doctors = Doctor::query()
            ->with('user:id,name,email,phone,role')
            ->latest()
            ->get();

        return $this->success($doctors, 'Data dokter berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'specialization' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'string', 'max:255'],
        ]);

        $doctor = DB::transaction(function () use ($validated): Doctor {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => $validated['password'],
                'role' => User::ROLE_DOKTER,
            ]);

            return Doctor::query()->create([
                'user_id' => $user->id,
                'specialization' => $validated['specialization'],
                'description' => $validated['description'] ?? null,
                'photo' => $validated['photo'] ?? null,
            ]);
        });

        return $this->success($doctor->load('user:id,name,email,phone,role'), 'Data dokter berhasil ditambahkan', 201);
    }

    public function show(Doctor $dokter): JsonResponse
    {
        return $this->success(
            $dokter->load('user:id,name,email,phone,role'),
            'Detail dokter berhasil diambil'
        );
    }

    public function update(Request $request, Doctor $dokter): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($dokter->user_id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'specialization' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($dokter, $validated): void {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => User::ROLE_DOKTER,
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $dokter->user->update($userData);

            $dokter->update([
                'specialization' => $validated['specialization'],
                'description' => $validated['description'] ?? null,
                'photo' => $validated['photo'] ?? null,
            ]);
        });

        return $this->success($dokter->fresh()->load('user:id,name,email,phone,role'), 'Data dokter berhasil diperbarui');
    }

    public function destroy(Doctor $dokter): JsonResponse
    {
        if ($dokter->schedules()->exists()) {
            return $this->error('Dokter tidak bisa dihapus karena masih memiliki jadwal.', 422);
        }

        DB::transaction(function () use ($dokter): void {
            $user = $dokter->user;
            $dokter->delete();
            $user?->delete();
        });

        return $this->success(null, 'Data dokter berhasil dihapus');
    }
}
