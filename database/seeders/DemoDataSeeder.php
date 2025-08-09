<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive demo data seeding...');
        
        // Disable assessment history tracking for seeding
        \DB::statement('SET @disable_assessment_history = 1;');

        // Create demo users
        $this->createDemoUsers();

        // Create schools if needed
        $this->createSchools();

        // Create students and assessments
        $this->createStudentsAndAssessments();

        $this->command->info('Demo data seeding completed successfully!');
    }

    private function createDemoUsers()
    {
        $this->command->info('Creating demo users...');

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@prathaminternational.org'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'sex' => 'M',
                'phone' => '012345678',
            ]
        );

        // Create coordinator user
        User::updateOrCreate(
            ['email' => 'coordinator@prathaminternational.org'],
            [
                'name' => 'Coordinator User',
                'password' => Hash::make('password'),
                'role' => 'coordinator',
                'is_active' => true,
                'sex' => 'F',
                'phone' => '012345679',
            ]
        );

        // Create teacher user
        $school = School::first();
        User::updateOrCreate(
            ['email' => 'teacher1@prathaminternational.org'],
            [
                'name' => 'Teacher One',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'school_id' => $school ? $school->id : null,
                'is_active' => true,
                'sex' => 'F',
                'phone' => '012345680',
                'holding_classes' => 'Grade 1, Grade 2',
            ]
        );

        $this->command->info('Demo users created successfully!');
    }

    private function createSchools()
    {
        $this->command->info('Creating schools...');

        $provinces = ['Phnom Penh', 'Kandal', 'Takeo', 'Kampot', 'Kampong Speu'];
        $districts = [
            'Phnom Penh' => ['Chamkar Mon', 'Daun Penh', 'Prampir Makara', 'Tuol Kouk', 'Dangkao'],
            'Kandal' => ['Ang Snuol', 'Kien Svay', 'Khsach Kandal', 'Lvea Aem', 'Mukh Kampul'],
            'Takeo' => ['Angkor Borei', 'Bati', 'Bourei Cholsar', 'Kiri Vong', 'Kaoh Andaet'],
            'Kampot' => ['Angkor Chey', 'Banteay Meas', 'Chhuk', 'Chum Kiri', 'Dang Tong'],
            'Kampong Speu' => ['Basedth', 'Chbar Mon', 'Kong Pisei', 'Aoral', 'Odongk'],
        ];

        $schoolCount = School::count();
        $neededSchools = 50 - $schoolCount;

        if ($neededSchools <= 0) {
            $this->command->info("Already have {$schoolCount} schools. Skipping school creation.");
            return;
        }

        for ($i = 1; $i <= $neededSchools; $i++) {
            $province = $provinces[array_rand($provinces)];
            $district = $districts[$province][array_rand($districts[$province])];
            
            School::create([
                'name' => "Primary School {$i}",
                'school_code' => 'SCH' . str_pad($schoolCount + $i, 4, '0', STR_PAD_LEFT),
                'province' => $province,
                'district' => $district,
                'subdistrict' => "Subdistrict {$i}",
                'address' => "Street {$i}, {$district}, {$province}",
                'contact_person' => "Director {$i}",
                'phone' => '012' . rand(100000, 999999),
                'email' => 'school' . ($schoolCount + $i) . '@example.com',
                'postal_code' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'education_service_area' => 'Area ' . rand(1, 5),
                'status' => 'active',
            ]);
        }

        $this->command->info("Created {$neededSchools} new schools!");
    }

    private function createStudentsAndAssessments()
    {
        $this->command->info('Creating students and assessments...');
        
        // Clear existing assessments and students for clean demo data
        $this->command->info('Clearing existing student and assessment data...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Assessment::truncate();
        \DB::table('student_teacher')->truncate();
        Student::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define assessment levels
        $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

        // Get starting student ID based on existing students
        $existingStudentCount = 0;
        $schools = School::take(50)->get();
        $totalStudentsCreated = $existingStudentCount;

        foreach ($schools as $schoolIndex => $school) {
            $schoolNumber = $schoolIndex + 1;
            $this->command->info("Processing school {$school->name} ({$schoolNumber}/50)...");

            // Create 10 students per school
            for ($s = 1; $s <= 10; $s++) {
                $sex = rand(0, 1) ? 'M' : 'F';
                $firstName = $sex === 'M' 
                    ? ['Sok', 'Chan', 'Phalla', 'Vuthy', 'Samnang', 'Dara', 'Sopheak', 'Veasna'][array_rand(['Sok', 'Chan', 'Phalla', 'Vuthy', 'Samnang', 'Dara', 'Sopheak', 'Veasna'])]
                    : ['Sreyneth', 'Sophea', 'Kunthea', 'Sina', 'Pisey', 'Srey Mom', 'Chanthy', 'Sokunthea'][array_rand(['Sreyneth', 'Sophea', 'Kunthea', 'Sina', 'Pisey', 'Srey Mom', 'Chanthy', 'Sokunthea'])];
                
                $lastName = ['Keo', 'Sok', 'Chan', 'Lim', 'Mao', 'Phan', 'Seng', 'Chea'][array_rand(['Keo', 'Sok', 'Chan', 'Lim', 'Mao', 'Phan', 'Seng', 'Chea'])];
                
                $age = rand(6, 12);
                $student = Student::create([
                    'student_code' => 'STU' . str_pad($totalStudentsCreated + 1, 6, '0', STR_PAD_LEFT),
                    'name' => $firstName . ' ' . $lastName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'nickname' => substr($firstName, 0, 3),
                    'sex' => $sex,
                    'gender' => $sex,
                    'birthdate' => Carbon::now()->subYears($age)->subDays(rand(0, 365)),
                    'age' => $age,
                    'grade' => rand(1, 6),
                    'class' => 'Class ' . rand(1, 3),
                    'section' => 'A',
                    'student_number' => $s,
                    'status' => 'active',
                    'school_id' => $school->id,
                    'parent_name' => 'Parent of ' . $firstName,
                    'parent_phone' => '012' . rand(100000, 999999),
                    'address' => "House " . rand(1, 100) . ", Street " . rand(1, 50),
                    'subdistrict' => $school->subdistrict,
                    'district' => $school->district,
                    'province' => $school->province,
                    'postal_code' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                ]);

                // Create assessments for each student
                $this->createStudentAssessments($student, $khmerLevels, $mathLevels);
                
                $totalStudentsCreated++;
            }

            // No need to update student count in this model structure
        }

        $this->command->info("Created {$totalStudentsCreated} students with assessments!");
    }

    private function createStudentAssessments($student, $khmerLevels, $mathLevels)
    {
        // Define assessment dates
        $baselineDate = Carbon::now()->subMonths(8)->addDays(rand(0, 14));
        $midlineDate = Carbon::now()->subMonths(4)->addDays(rand(0, 14));
        $endlineDate = Carbon::now()->subDays(rand(0, 14));

        // Create baseline assessments
        $this->createAssessment($student, 'baseline', 'khmer', $khmerLevels, $baselineDate);
        $this->createAssessment($student, 'baseline', 'math', $mathLevels, $baselineDate);

        // Create midline assessments (70% chance)
        if (rand(1, 100) <= 70) {
            $this->createAssessment($student, 'midline', 'khmer', $khmerLevels, $midlineDate, 1);
            $this->createAssessment($student, 'midline', 'math', $mathLevels, $midlineDate, 1);
        }

        // Create endline assessments (50% chance)
        if (rand(1, 100) <= 50) {
            $this->createAssessment($student, 'endline', 'khmer', $khmerLevels, $endlineDate, 2);
            $this->createAssessment($student, 'endline', 'math', $mathLevels, $endlineDate, 2);
        }
    }

    private function createAssessment($student, $cycle, $subject, $levels, $date, $progressionLevel = 0)
    {
        // Simulate realistic distribution based on progression
        $weights = match($progressionLevel) {
            0 => [35, 25, 20, 10, 5, 3, 2], // Baseline - more beginners
            1 => [20, 25, 25, 15, 10, 3, 2], // Midline - some progression
            2 => [10, 20, 25, 20, 15, 7, 3], // Endline - more advanced
            default => [35, 25, 20, 10, 5, 3, 2]
        };

        // For Math, use 6 weights instead of 7
        if ($subject === 'math') {
            $weights = array_slice($weights, 0, 6);
        }

        // Select level based on weighted random
        $levelIndex = $this->weightedRandom($weights);
        $level = $levels[$levelIndex];

        // Generate a realistic score based on level
        $score = match($levelIndex) {
            0 => rand(0, 40),    // Beginner
            1 => rand(30, 60),   // Letter/1-Digit
            2 => rand(50, 75),   // Word/2-Digit
            3 => rand(60, 85),   // Paragraph/Subtraction
            4 => rand(70, 90),   // Story/Division
            5 => rand(75, 95),   // Comp. 1/Word Problem
            6 => rand(80, 100),  // Comp. 2
            default => rand(40, 80)
        };

        Assessment::create([
            'student_id' => $student->id,
            'cycle' => $cycle,
            'subject' => $subject,
            'level' => $level,
            'score' => $score,
            'assessed_at' => $date,
            'is_locked' => false,
        ]);
    }

    private function weightedRandom($weights)
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        
        $cumulative = 0;
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $index;
            }
        }
        
        return count($weights) - 1;
    }
}