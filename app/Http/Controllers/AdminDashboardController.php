<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users'    => User::count(),
                'total_patients' => Patient::count(),
                'archived_patients' => Patient::onlyTrashed()->count(),
            ]
        ]);
    }
}
