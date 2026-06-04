<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ModulePlaceholderController extends Controller
{
    use ApiResponse;

    public function index(string $module): JsonResponse
    {
        return $this->success([
            'module' => $module,
            'owner' => $this->moduleOwner($module),
            'status' => 'placeholder',
        ], "Endpoint {$module} siap diisi module terkait");
    }

    private function moduleOwner(string $module): string
    {
        return match ($module) {
            'dokter', 'jadwal', 'users' => 'Orang 2 - Database & CRUD',
            'reservasi' => 'Orang 3 - Reservation & Business Logic',
            default => 'Project Lead',
        };
    }
}
