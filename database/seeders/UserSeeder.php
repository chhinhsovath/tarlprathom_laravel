<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample school first
        $school = School::create([
            'name' => 'Phnom Penh Primary School',
            'province' => 'Phnom Penh',
            'district' => 'Chamkarmon',
            'commune' => 'Tonle Bassac',
            'cluster' => 'Central',
        ]);

        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tarlconnect.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Mentor users
        User::create([
            'name' => 'Mentor One',
            'email' => 'mentor1@tarlconnect.com',
            'password' => Hash::make('password'),
            'role' => 'mentor',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Mentor Two',
            'email' => 'mentor2@tarlconnect.com',
            'password' => Hash::make('password'),
            'role' => 'mentor',
            'is_active' => true,
        ]);

        // Teacher users
        User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@tarlconnect.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@tarlconnect.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        // Viewer user (MoEYS)
        User::create([
            'name' => 'MoEYS Viewer',
            'email' => 'viewer@moeys.gov.kh',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }
}
