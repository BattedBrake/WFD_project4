<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');
    Route::get('/dokter/create', [AdminController::class, 'dokterCreate'])->name('dokter.create');
    Route::post('/dokter', [AdminController::class, 'dokterStore'])->name('dokter.store');
    Route::get('/dokter/{id}', [AdminController::class, 'dokterShow'])->name('dokter.show');
    Route::get('/dokter/{id}/edit', [AdminController::class, 'dokterEdit'])->name('dokter.edit');
    Route::put('/dokter/{id}', [AdminController::class, 'dokterUpdate'])->name('dokter.update');
    Route::delete('/dokter/{id}', [AdminController::class, 'dokterDestroy'])->name('dokter.destroy');

    Route::get('/jadwal', [AdminController::class, 'jadwalIndex'])->name('jadwal.index');
    Route::get('/jadwal/create', [AdminController::class, 'jadwalCreate'])->name('jadwal.create');
    Route::post('/jadwal', [AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [AdminController::class, 'jadwalEdit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');

    Route::get('/reservasi', [AdminController::class, 'reservasiIndex'])->name('reservasi.index');
    Route::get('/reservasi/{id}', [AdminController::class, 'reservasiShow'])->name('reservasi.show');
    Route::delete('/reservasi/{id}', [AdminController::class, 'reservasiDestroy'])->name('reservasi.destroy');

    Route::get('/user', [AdminController::class, 'userIndex'])->name('user.index');
    Route::get('/user/{id}/edit', [AdminController::class, 'userEdit'])->name('user.edit');
    Route::put('/user/{id}', [AdminController::class, 'userUpdate'])->name('user.update');
    Route::delete('/user/{id}', [AdminController::class, 'userDestroy'])->name('user.destroy');
});
