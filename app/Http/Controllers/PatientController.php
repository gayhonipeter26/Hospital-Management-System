<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients with search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(phone_code, phone) like ?", ["%{$search}%"])
                      ->orWhere('residence', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Patients/Index', [
            'patients' => $patients,
            'filters'  => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return Inertia::render('Patients/Create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone_code'  => 'required|string',
            'phone'       => 'required|string',
            'gender'      => 'required|string',
            'dob'         => 'required|date',
            'id_number'   => 'required|string|unique:patients',
            'nationality' => 'required|string',
            'residence'   => 'required|string',
            'country'     => 'required|string',
            'email'       => 'nullable|email',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        return Inertia::render('Patients/Show', [
            'patient' => $patient,
        ]);
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        return Inertia::render('Patients/Edit', [
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone_code'  => 'required|string',
            'phone'       => 'required|string',
            'gender'      => 'required|string',
            'dob'         => 'required|date',
            'id_number'   => 'required|string|unique:patients,id_number,' . $patient->id,
            'nationality' => 'required|string',
            'residence'   => 'required|string',
            'country'     => 'required|string',
            'email'       => 'nullable|email',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Archive (soft delete) the specified patient.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.archive')->with('success', 'Patient archived successfully.');
    }

    /**
     * Display archived patients.
     */
    public function archive(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::onlyTrashed()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(phone_code, phone) like ?", ["%{$search}%"])
                      ->orWhere('residence', 'like', "%{$search}%");
                });
            })
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Patients/Archive', [
            'patients' => $patients,
            'filters'  => $request->only('search'),
        ]);
    }

    /**
     * Restore a soft-deleted patient.
     */
    public function restore($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.index')->with('success', 'Patient restored successfully.');
    }

    /**
     * Permanently delete a patient.
     */
    public function forceDelete($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->forceDelete();

        return redirect()->route('patients.archive')->with('success', 'Patient permanently deleted.');
    }
}
