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
        // Get the first school or create a sample school
        $school = School::first();
        if (! $school) {
            $school = School::create([
                'name' => 'Phnom Penh Primary School',
                'school_name' => 'Phnom Penh Primary School',
                'school_code' => 'PPP001',
                'province' => 'Phnom Penh',
                'district' => 'Chamkarmon',
                'commune' => 'Tonle Bassac',
                'cluster' => 'Central',
            ]);
        }

        // Admin user
        User::firstOrCreate(
            ['email' => 'kairav@prathaminternational.org'],
            [
                'name' => 'Kairav Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Additional admin user
        User::firstOrCreate(
            ['email' => 'admin@prathaminternational.org'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Mentor users
        User::firstOrCreate(
            ['email' => 'mentor1@prathaminternational.org'],
            [
                'name' => 'Mentor One',
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mentor2@prathaminternational.org'],
            [
                'name' => 'Mentor Two',
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
            ]
        );

        // Teacher users
        User::firstOrCreate(
            ['email' => 'teacher1@prathaminternational.org'],
            [
                'name' => 'Teacher One',
                'password' => Hash::make('admin123'),
                'role' => 'teacher',
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'teacher2@prathaminternational.org'],
            [
                'name' => 'Teacher Two',
                'password' => Hash::make('admin123'),
                'role' => 'teacher',
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );

        // Coordinator user
        User::firstOrCreate(
            ['email' => 'coordinator@prathaminternational.org'],
            [
                'name' => 'Coordinator One',
                'password' => Hash::make('admin123'),
                'role' => 'coordinator',
                'is_active' => true,
            ]
        );

        // Viewer user
        User::firstOrCreate(
            ['email' => 'viewer@prathaminternational.org'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('admin123'),
                'role' => 'viewer',
                'is_active' => true,
            ]
        );
    }
}
