<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('dokter', DoctorController::class)->only(['index', 'show']);
        Route::apiResource('jadwal', ScheduleController::class)->only(['index', 'show']);
        Route::post('/jadwal', [ScheduleController::class, 'store'])->middleware('role:admin,dokter');
        Route::apiResource('reservasi', ReservationController::class);

        Route::middleware('role:admin')->group(function (): void {
            Route::apiResource('dokter', DoctorController::class)->except(['index', 'show']);
            Route::apiResource('jadwal', ScheduleController::class)->only(['update', 'destroy']);
            Route::apiResource('users', UserController::class);
        });
    });
});
