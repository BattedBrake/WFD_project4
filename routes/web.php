<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::view('/', 'welcome')->name('home');

Route::get('/cek-db', function () {
    abort_unless(app()->isLocal(), 404);

    try {
        DB::connection()->getPdo();

        $tables = ['users', 'doctors', 'schedules', 'reservations'];

        return response()->json([
            'laravel' => 'ok',
            'database' => 'connected',
            'connection' => config('database.default'),
            'database_name' => DB::connection()->getDatabaseName(),
            'tables' => collect($tables)
                ->mapWithKeys(fn (string $table): array => [
                    $table => Schema::hasTable($table) ? 'exists' : 'missing',
                ]),
        ]);
    } catch (\Throwable $exception) {
        return response()->json([
            'laravel' => 'ok',
            'database' => 'error',
            'message' => $exception->getMessage(),
        ], 500);
    }
})->name('health.database');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter'])
        ->middleware('role:dokter')
        ->name('dokter.dashboard');

    Route::get('/pasien/dashboard', [DashboardController::class, 'pasien'])
        ->middleware('role:pasien')
        ->name('pasien.dashboard');

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::get('/reservations/create', [ReservationController::class, 'create'])
        ->middleware('role:pasien')
        ->name('reservations.create');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->middleware('role:pasien')
        ->name('reservations.store');

    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->middleware('role:pasien')
        ->name('reservations.cancel');

    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])
        ->middleware('role:dokter')
        ->name('reservations.status');
});

require __DIR__.'/admin.php';
