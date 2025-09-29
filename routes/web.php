<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes that require authentication and admin role.
|
*/
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard route
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Patients resource routes
    Route::resource('patients', PatientController::class);

    // Archive routes
    Route::get('patients/archive', [PatientController::class, 'archive'])->name('patients.archive');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::delete('patients/{id}/force-delete', [PatientController::class, 'forceDelete'])->name('patients.forceDelete');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes accessible without admin middleware.
|
*/
// Home route
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Include other routes
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
