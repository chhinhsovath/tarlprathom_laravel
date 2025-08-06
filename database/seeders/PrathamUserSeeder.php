<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PrathamUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get first school for teachers (or create one if none exists)
        $school = School::first();
        if (! $school) {
            $school = School::create([
                'name' => 'Pratham School',
                'address' => '123 Main Street',
                'contact_person' => 'School Admin',
                'phone' => '0812345678',
                'email' => 'school@prathaminternational.org',
                'province' => 'Bangkok',
                'district' => 'District 1',
                'subdistrict' => 'Subdistrict 1',
                'postal_code' => '10001',
                'school_code' => 'PRATH001',
                'education_service_area' => 'Area 1',
                'status' => 'active',
            ]);
        }

        // Create Coordinator
        $coordinator = User::create([
            'name' => 'Coordinator User',
            'email' => 'coordinator@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'coordinator',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Admin
        $admin = User::create([
            'name' => 'Kairav Admin',
            'email' => 'kairav@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Mentor
        $mentor = User::create([
            'name' => 'Mentor One',
            'email' => 'mentor1@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'mentor',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Assign mentor to the school
        $mentor->assignedSchools()->attach($school->id);

        // Create Teacher
        $teacher = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'teacher',
            'school_id' => $school->id,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Viewer
        $viewer = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@prathaminternational.org',
            'password' => Hash::make('admin123'),
            'role' => 'viewer',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        $this->command->info('Pratham user accounts created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Coordinator: coordinator@prathaminternational.org / admin123');
        $this->command->info('Admin: kairav@prathaminternational.org / admin123');
        $this->command->info('Mentor: mentor1@prathaminternational.org / admin123');
        $this->command->info('Teacher: teacher1@prathaminternational.org / admin123');
        $this->command->info('Viewer: viewer@prathaminternational.org / admin123');
    }
}
