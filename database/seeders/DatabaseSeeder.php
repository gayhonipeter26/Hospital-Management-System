<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
  public function run(): void
{
    // Default Admin
    User::updateOrCreate(
        ['email' => 'admin@hospital.com'],
        [
            'name' => 'Super Admin',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'), // change to secure password
            'role' => 'admin',
        ]
    );
}
}
