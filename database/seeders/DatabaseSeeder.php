<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Visit;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Create default Admin user
        User::updateOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // ✅ Create a sample patient (matches your table)
        $patient = Patient::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'first_name'     => 'John',
                'last_name'      => 'Doe',
                'phone_code'     => '+254',
                'phone'          => '0712345678',
                'gender'         => 'Male',
                'dob'            => '1990-05-20',
                'id_number'      => '12345678',
                'blood_type'     => 'O+',
                'marital_status' => 'Single',
                'nationality'    => 'Kenyan',
                'residence'      => 'Nairobi',
                'city'           => 'Nairobi',
                'country'        => 'Kenya',
                'occupation'     => 'Software Engineer',
                'religion'       => 'Christian',
                'notes'          => 'No known allergies.',
            ]
        );

        // ✅ Create a sample visit for this patient
        Visit::updateOrCreate(
            ['patient_id' => $patient->id],
            [
                'start_date'     => now(),
                'status'         => 'Check In',
                'patient_type'   => 'Out Patient',
                'scheduled'      => false,
                'notes'          => 'Routine checkup visit.',
                'department'     => 'General',
                'practitioner'   => 'Dr. Smith',
                'practitioner_name' => 'Dr. Smith',
            ]
        );
    }
}
