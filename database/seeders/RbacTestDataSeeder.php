<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test schools (only using available columns)
        $schools = [
            [
                'school_code' => 'PS001',
                'school_name' => 'Phnom Penh Primary School',
                'province' => 'Phnom Penh',
                'district' => 'Daun Penh',
                'cluster' => 'Central Cluster',
            ],
            [
                'school_code' => 'PS002',
                'school_name' => 'Siem Reap Community School',
                'province' => 'Siem Reap',
                'district' => 'Siem Reap',
                'cluster' => 'Tourism Cluster',
            ],
            [
                'school_code' => 'PS003',
                'school_name' => 'Battambang Rural School',
                'province' => 'Battambang',
                'district' => 'Battambang',
                'cluster' => 'Rural Cluster',
            ],
            [
                'school_code' => 'PS004',
                'school_name' => 'Kampong Cham Elementary',
                'province' => 'Kampong Cham',
                'district' => 'Kampong Cham',
                'cluster' => 'River Cluster',
            ],
            [
                'school_code' => 'PS005',
                'school_name' => 'Kandal Province School',
                'province' => 'Kandal',
                'district' => 'Kandal Stung',
                'cluster' => 'Urban Cluster',
            ],
        ];

        foreach ($schools as $schoolData) {
            PilotSchool::firstOrCreate(
                ['school_code' => $schoolData['school_code']], 
                $schoolData
            );
        }

        $createdSchools = PilotSchool::all();

        // Create RBAC test users
        $users = [
            // Admin User
            [
                'name' => 'Admin User',
                'email' => 'admin@tarl.gov.kh',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'province' => 'Phnom Penh',
                'district' => 'Daun Penh',
                'phone' => '012-000001',
                'sex' => 'M',
                'is_active' => true,
            ],
            
            // Coordinators
            [
                'name' => 'Sophy Coordinator',
                'email' => 'coordinator1@tarl.gov.kh',
                'password' => Hash::make('Coord123!'),
                'role' => 'coordinator',
                'province' => 'Phnom Penh',
                'district' => 'Chamkar Mon',
                'phone' => '012-000002',
                'sex' => 'F',
                'is_active' => true,
            ],
            [
                'name' => 'Pisach Regional Coordinator',
                'email' => 'coordinator2@tarl.gov.kh',
                'password' => Hash::make('Coord123!'),
                'role' => 'coordinator',
                'province' => 'Siem Reap',
                'district' => 'Siem Reap',
                'phone' => '015-000003',
                'sex' => 'M',
                'is_active' => true,
            ],

            // Mentors
            [
                'name' => 'Channary Mentor',
                'email' => 'mentor1@tarl.gov.kh',
                'password' => Hash::make('Mentor123!'),
                'role' => 'mentor',
                'province' => 'Phnom Penh',
                'district' => 'Toul Kork',
                'phone' => '017-000004',
                'sex' => 'F',
                'is_active' => true,
            ],
            [
                'name' => 'Sopheak Education Mentor',
                'email' => 'mentor2@tarl.gov.kh',
                'password' => Hash::make('Mentor123!'),
                'role' => 'mentor',
                'province' => 'Battambang',
                'district' => 'Battambang',
                'phone' => '011-000005',
                'sex' => 'M',
                'is_active' => true,
            ],
            [
                'name' => 'Sreyleak Regional Mentor',
                'email' => 'mentor3@tarl.gov.kh',
                'password' => Hash::make('Mentor123!'),
                'role' => 'mentor',
                'province' => 'Kandal',
                'district' => 'Kandal Stung',
                'phone' => '016-000006',
                'sex' => 'F',
                'is_active' => true,
            ],

            // Teachers
            [
                'name' => 'Dara Teacher Grade 1',
                'email' => 'teacher1@school001.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 1, // Phnom Penh Primary School
                'province' => 'Phnom Penh',
                'district' => 'Daun Penh',
                'phone' => '012-111001',
                'sex' => 'M',
                'holding_classes' => 'Grade 1A, Grade 1B',
                'is_active' => true,
            ],
            [
                'name' => 'Mayura Teacher Grade 2',
                'email' => 'teacher2@school001.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 1, // Phnom Penh Primary School
                'province' => 'Phnom Penh',
                'district' => 'Daun Penh',
                'phone' => '015-111002',
                'sex' => 'F',
                'holding_classes' => 'Grade 2A',
                'is_active' => true,
            ],
            [
                'name' => 'Raksmey Teacher Grade 3',
                'email' => 'teacher3@school002.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 2, // Siem Reap Community School
                'province' => 'Siem Reap',
                'district' => 'Siem Reap',
                'phone' => '063-222001',
                'sex' => 'F',
                'holding_classes' => 'Grade 3A, Grade 3B',
                'is_active' => true,
            ],
            [
                'name' => 'Vicheka Teacher Grade 1',
                'email' => 'teacher4@school003.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 3, // Battambang Rural School
                'province' => 'Battambang',
                'district' => 'Battambang',
                'phone' => '053-333001',
                'sex' => 'M',
                'holding_classes' => 'Grade 1A',
                'is_active' => true,
            ],
            [
                'name' => 'Bopha Teacher Grade 2',
                'email' => 'teacher5@school004.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 4, // Kampong Cham Elementary
                'province' => 'Kampong Cham',
                'district' => 'Kampong Cham',
                'phone' => '042-444001',
                'sex' => 'F',
                'holding_classes' => 'Grade 2A, Grade 2B',
                'is_active' => true,
            ],
            [
                'name' => 'Kimhout Teacher Grade 3',
                'email' => 'teacher6@school005.edu.kh',
                'password' => Hash::make('Teacher123!'),
                'role' => 'teacher',
                'school_id' => 5, // Kandal Province School
                'province' => 'Kandal',
                'district' => 'Kandal Stung',
                'phone' => '024-555001',
                'sex' => 'M',
                'holding_classes' => 'Grade 3A',
                'is_active' => true,
            ],

            // Viewers
            [
                'name' => 'Data Analyst Viewer',
                'email' => 'viewer1@tarl.gov.kh',
                'password' => Hash::make('Viewer123!'),
                'role' => 'viewer',
                'province' => 'Phnom Penh',
                'district' => 'Chamkar Mon',
                'phone' => '012-000007',
                'sex' => 'F',
                'is_active' => true,
            ],
            [
                'name' => 'Research Observer',
                'email' => 'viewer2@tarl.gov.kh',
                'password' => Hash::make('Viewer123!'),
                'role' => 'viewer',
                'province' => 'Siem Reap',
                'district' => 'Siem Reap',
                'phone' => '063-000008',
                'sex' => 'M',
                'is_active' => true,
            ],

            // Inactive user for testing
            [
                'name' => 'Inactive User Test',
                'email' => 'inactive@tarl.gov.kh',
                'password' => Hash::make('Inactive123!'),
                'role' => 'teacher',
                'school_id' => 1,
                'province' => 'Phnom Penh',
                'district' => 'Daun Penh',
                'phone' => '012-000009',
                'sex' => 'F',
                'is_active' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']], 
                $userData
            );
        }

        // Assign schools to mentors
        $mentors = User::where('role', 'mentor')->get();
        
        // Mentor 1: Assigned to Phnom Penh and Kandal schools
        if ($mentors->count() >= 1) {
            $mentors[0]->assignedPilotSchools()->syncWithoutDetaching([1, 5]); // Phnom Penh Primary School, Kandal Province School
        }
        
        // Mentor 2: Assigned to Battambang school
        if ($mentors->count() >= 2) {
            $mentors[1]->assignedPilotSchools()->syncWithoutDetaching([3]); // Battambang Rural School
        }
        
        // Mentor 3: Assigned to Siem Reap and Kampong Cham schools
        if ($mentors->count() >= 3) {
            $mentors[2]->assignedPilotSchools()->syncWithoutDetaching([2, 4]); // Siem Reap Community School, Kampong Cham Elementary
        }

        // Create test students for teachers
        $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
        
        foreach ($teachers as $teacher) {
            if (!$teacher->school_id) continue;
            
            // Create 8-12 students per teacher
            $studentCount = rand(8, 12);
            
            for ($i = 1; $i <= $studentCount; $i++) {
                $maleNames = ['Sopheak', 'Pisach', 'Dara', 'Kimhout', 'Vicheka', 'Sokha', 'Rithy', 'Narith'];
                $femaleNames = ['Channary', 'Mayura', 'Sreyleak', 'Bopha', 'Raksmey', 'Sophy', 'Sreypov', 'Sovan'];
                
                $sex = rand(0, 1) ? 'M' : 'F';
                $names = $sex === 'M' ? $maleNames : $femaleNames;
                $name = $names[array_rand($names)] . ' Student ' . $i;
                
                $student = Student::create([
                    'student_code' => 'STU' . str_pad($teacher->school_id, 3, '0', STR_PAD_LEFT) . str_pad($i + ($teacher->id * 100), 4, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'sex' => $sex,
                    'date_of_birth' => now()->subYears(rand(6, 12))->subDays(rand(1, 365)),
                    'school_id' => $teacher->school_id,
                    'teacher_id' => $teacher->id,
                    'class' => 'Class ' . rand(1, 3) . chr(65 + rand(0, 2)), // Class 1A, 2B, etc.
                    'home_province' => $teacher->province,
                    'home_district' => $teacher->district,
                    'home_commune' => $teacher->commune ?: 'Test Commune',
                    'home_village' => $teacher->village ?: 'Test Village',
                    'guardian_name' => 'Guardian of ' . $name,
                    'guardian_phone' => '012-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'enrollment_date' => now()->subMonths(rand(1, 12)),
                ]);
                
                // Note: Assessment creation skipped due to schema differences
            }
        }

        // Note: Mentoring visits creation skipped due to schema differences

        $this->command->info('RBAC test data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 5 pilot schools');
        $this->command->info('- 15 users (1 admin, 2 coordinators, 3 mentors, 6 teachers, 2 viewers, 1 inactive)');
        $this->command->info('- ' . Student::count() . ' students');
        
        $this->command->info("\nTest Login Credentials:");
        $this->command->info("Admin: admin@tarl.gov.kh / Admin123!");
        $this->command->info("Coordinator: coordinator1@tarl.gov.kh / Coord123!");
        $this->command->info("Mentor: mentor1@tarl.gov.kh / Mentor123!");
        $this->command->info("Teacher: teacher1@school001.edu.kh / Teacher123!");
        $this->command->info("Viewer: viewer1@tarl.gov.kh / Viewer123!");
    }
}