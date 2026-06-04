<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_DOKTER => redirect()->route('dokter.dashboard'),
            default => redirect()->route('pasien.dashboard'),
        };
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function dokter(): View
    {
        return view('dashboards.dokter');
    }

    public function pasien(): View
    {
        return view('dashboards.pasien');
    }
}
