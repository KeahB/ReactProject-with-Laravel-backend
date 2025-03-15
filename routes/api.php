<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/appointments', [AppointmentController::class, 'store']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Admin-only routes (Protected using middleware)
    Route::middleware('admin')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index']); // View all appointments
        Route::get('/appointments/{id}', [AppointmentController::class, 'show']); // View a specific appointment
        Route::put('/appointments/{id}', [AppointmentController::class, 'update']); // Update an appointment
        Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']); // Delete an appointment
    });
});
