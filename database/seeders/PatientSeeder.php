<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::factory()->count(5)->create(); // uses factory if you have one

        // or manually insert demo patients
        Patient::insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '0712345678',
                'gender' => 'Male',
                'dob' => '1990-05-20',
                'address' => 'Nairobi',
                'id_number' => '12345678',
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Wanjiku',
                'email' => 'mary@example.com',
                'phone' => '0723456789',
                'gender' => 'Female',
                'dob' => '1995-03-15',
                'address' => 'Mombasa',
                'id_number' => '87654321',
            ],
        ]);
    }
}
