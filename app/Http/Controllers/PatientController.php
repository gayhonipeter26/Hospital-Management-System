<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PatientController extends Controller
{
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

    // ✅ Show Patient Creation Page
    public function create()
    {
        return Inertia::render('Patients/Create');
    }

    // ✅ Store Patient and redirect to visits.create with pre-selected patient
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone_code'  => 'required|string',
            'phone'       => [
                'required',
                'regex:/^[0-9]+$/',
            ],
            'gender'      => 'required|string',
            'dob'         => 'required|date',
            'id_number'   => 'nullable|string|unique:patients,id_number',
            'nationality' => 'required|string',
            'residence'   => 'required|string',
            'country'     => 'nullable|string',
            'email'       => 'nullable|email',
        ]);

        // ✅ Calculate age from DOB
        $age = Carbon::parse($validated['dob'])->age;

        if ($age >= 18 && empty($request->id_number)) {
            return back()
                ->withErrors(['id_number' => 'ID number is required for adults (18 years and above).'])
                ->withInput();
        }

        if (empty($validated['id_number'])) {
            $validated['id_number'] = null;
        }

        // ✅ Create patient
        $patient = Patient::create($validated);

        // ✅ Redirect to visits.create with pre-selected patient
        return redirect()
            ->route('visits.create', ['patient_id' => $patient->id])
            ->with('success', 'Patient saved successfully.');
    }

    public function show(Patient $patient)
    {
        return Inertia::render('Patients/Show', [
            'patient' => $patient,
        ]);
    }

    public function edit(Patient $patient)
    {
        return Inertia::render('Patients/Edit', [
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone_code'  => 'required|string',
            'phone'       => [
                'required',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) use ($request) {
                    $rules = [
                        '+254' => 9,
                        '+256' => 9,
                        '+255' => 9,
                        '+250' => 9,
                        '+234' => 10,
                        '+1'   => 10,
                        '+44'  => 10,
                        '+91'  => 10,
                    ];
                    $code = $request->input('phone_code');
                    if (isset($rules[$code]) && strlen($value) !== $rules[$code]) {
                        $fail("The phone number must be {$rules[$code]} digits for {$code}.");
                    }
                }
            ],
            'gender'      => 'required|string',
            'dob'         => 'required|date',
            'id_number'   => [
                'nullable',
                'string',
                'unique:patients,id_number,' . $patient->id,
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->dob) {
                        $age = Carbon::parse($request->dob)->age;
                        if ($age >= 18 && empty($value)) {
                            $fail('The ID number is required for adults.');
                        }
                    }
                }
            ],
            'nationality' => 'required|string',
            'residence'   => 'required|string',
            'country'     => 'nullable|string',
            'email'       => 'nullable|email',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.archive')->with('success', 'Patient archived successfully.');
    }

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

    public function restore($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.index')->with('success', 'Patient restored successfully.');
    }

    public function forceDelete($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->forceDelete();

        return redirect()->route('patients.archive')->with('success', 'Patient permanently deleted.');
    }
}
