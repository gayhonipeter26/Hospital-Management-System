<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Patient;

class VisitController extends Controller
{
    /**
     * Display all visits.
     */
    public function index()
    {
        return Inertia::render('Visits/Index', [
            'visits' => Visit::with('patient')->latest()->get(),
        ]);
    }

    /**
     * Show form to create a new visit.
     */
  public function create(Request $request)
{
    $patients = \App\Models\Patient::all();
    $selectedPatientId = $request->query('patient_id');

    return Inertia::render('Visits/Create', [
        'patients' => $patients,
        'selectedPatientId' => $selectedPatientId,
    ]);
}

    /**
     * Store a newly created visit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'start_date' => 'required|date',
            'status' => 'required|in:Check In,Check Out,Pending',
            'patient_type' => 'required|in:Out Patient,In Patient',
            'scheduled' => 'boolean',
            'notes' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'practitioner' => 'nullable|string|max:255',
            'practitioner_name' => 'nullable|string|max:255',
        ]);

        // Sanitize patient_type to match enum exactly
        $validated['patient_type'] = match(strtolower($validated['patient_type'])) {
            'outpatient', 'out patient' => 'Out Patient',
            'inpatient', 'in patient' => 'In Patient',
            default => 'Out Patient',
        };

        Visit::create($validated);

        return redirect()->route('visits.index')->with('success', 'Visit added successfully.');
    }

    /**
     * Display a single visit.
     */
    public function show(Visit $visit)
    {
        return Inertia::render('Visits/Show', [
            'visit' => $visit->load('patient'),
        ]);
    }

    /**
     * Show form to edit a visit.
     */
    public function edit($id)
    {
        $visit = Visit::with('patient')->findOrFail($id);
        return Inertia::render('Visits/Edit', ['visit' => $visit]);
    }

    /**
     * Update an existing visit.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'status' => 'required|in:Check In,Check Out,Pending',
            'patient_type' => 'required|in:Out Patient,In Patient',
            'notes' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'practitioner' => 'nullable|string|max:255',
            'practitioner_name' => 'nullable|string|max:255',
        ]);

        // Sanitize patient_type to match enum exactly
        $validated['patient_type'] = match(strtolower($validated['patient_type'])) {
            'outpatient', 'out patient' => 'Out Patient',
            'inpatient', 'in patient' => 'In Patient',
            default => 'Out Patient',
        };

        $visit = Visit::findOrFail($id);
        $visit->update($validated);

        return redirect()->route('visits.index')->with('success', 'Visit updated successfully!');
    }

    /**
     * Delete a visit.
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()->route('visits.index')->with('success', 'Visit deleted successfully.');
    }
}
