<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\Patient;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        $patient = Patient::first();

        if ($patient) {
            Visit::create([
                'patient_id' => $patient->id,
                'start_date' => now(),
                'status' => 'Check In',
                'patient_type' => 'Out Patient',
                'scheduled' => false,
                'notes' => 'Routine checkup visit.',
                'department' => 'Triage',
                'practitioner' => 'Dr. Kamau',
                'practitioner_name' => 'Dr. Kamau',
            ]);
        }
    }
}
