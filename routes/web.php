<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;


Route::middleware(['auth', 'admin'])->group(function () {
    // Patients resource routes
    Route::resource('patients', PatientController::class);

    // Archive routes
    Route::get('patients/archive', [PatientController::class, 'archive'])->name('patients.archive');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::delete('patients/{id}/force-delete', [PatientController::class, 'forceDelete'])->name('patients.forceDelete');

    // Dashboard route
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

// Home route
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Other routes
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
