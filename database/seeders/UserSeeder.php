<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
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
            'name' => 'Kairav Admin',
            'email' => 'kairav@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Mentor user
        User::create([
            'name' => 'Mentor One',
            'email' => 'mentor1@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'mentor',
            'is_active' => true,
        ]);

        // Teacher user
        User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'teacher',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        // Viewer user
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }
}
