<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'admin'])->group(function () {

    // ----------------------------
    // Dashboard
    // ----------------------------
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ----------------------------
    // Patients
    // ----------------------------
    Route::resource('patients', PatientController::class);

    // Archive routes
    Route::get('patients/archive', [PatientController::class, 'archive'])->name('patients.archive');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    Route::delete('patients/{id}/force-delete', [PatientController::class, 'forceDelete'])->name('patients.forceDelete');

    // ----------------------------
    // Visits
    // ----------------------------
    // Global visits (admin-wide)
    Route::resource('visits', VisitController::class);

    // If you really want nested routes under patients (optional)
    Route::prefix('patients/{patient}')->group(function () {
        Route::get('visits', [VisitController::class, 'patientVisits'])->name('patients.visits.index');
        Route::get('visits/create', [VisitController::class, 'createForPatient'])->name('patients.visits.create');
        Route::post('visits', [VisitController::class, 'storeForPatient'])->name('patients.visits.store');
    });
});

// ----------------------------
// Public routes
// ----------------------------
Route::get('/countries', [CountryController::class, 'index']);
Route::post('/countries', [CountryController::class, 'store']);

// ----------------------------
// Home
// ----------------------------
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// ----------------------------
// Other routes
// ----------------------------
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
