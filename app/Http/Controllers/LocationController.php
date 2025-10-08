<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    // Get all locations
    public function index()
    {
        return response()->json(Location::all());
    }

    // Add a new location (country or nationality)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:country,nationality',
        ]);

        $location = Location::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return response()->json($location, 201);
    }
}
