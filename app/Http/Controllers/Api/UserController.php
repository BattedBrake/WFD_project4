<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'phone', 'role', 'created_at', 'updated_at')
            ->latest()
            ->get();

        return $this->success($users, 'Data user berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::query()->create($validated);

        return $this->success($user->only(['id', 'name', 'email', 'phone', 'role']), 'Data user berhasil ditambahkan', 201);
    }

    public function show(User $user): JsonResponse
    {
        return $this->success(
            $user->only(['id', 'name', 'email', 'phone', 'role', 'created_at', 'updated_at']),
            'Detail user berhasil diambil'
        );
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return $this->success(
            $user->fresh()->only(['id', 'name', 'email', 'phone', 'role']),
            'Data user berhasil diperbarui'
        );
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->is($user)) {
            return $this->error('User yang sedang login tidak bisa menghapus akunnya sendiri.', 422);
        }

        $user->delete();

        return $this->success(null, 'Data user berhasil dihapus');
    }
}
