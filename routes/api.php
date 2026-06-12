<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModulePlaceholderController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/dokter', [ModulePlaceholderController::class, 'index'])->defaults('module', 'dokter');
        Route::get('/jadwal', [ModulePlaceholderController::class, 'index'])->defaults('module', 'jadwal');
        Route::get('/reservasi', [ModulePlaceholderController::class, 'index'])->defaults('module', 'reservasi');
        Route::get('/users', [ModulePlaceholderController::class, 'index'])
            ->middleware('role:admin')
            ->defaults('module', 'users');
    });
});
