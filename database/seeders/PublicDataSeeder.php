<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicDataSeeder extends Seeder
{
    /**
     * Run the database seeds for public display with realistic TaRL data distribution.
     */
    public function run()
    {
        // Get or create sample schools from different provinces
        $provinces = [
            'Phnom Penh', 'Kandal', 'Kampong Cham', 'Kampong Speu', 'Takeo',
            'Kampot', 'Siem Reap', 'Battambang', 'Banteay Meanchey', 'Kampong Thom'
        ];

        $schools = [];
        foreach ($provinces as $idx => $province) {
            // Check if schools exist for this province
            $school = School::where('sclProvinceName', $province)->first();
            if (!$school) {
                // Try to find by province code
                $school = School::whereNotNull('sclProvinceName')
                    ->where('sclProvinceName', '!=', '')
                    ->skip($idx * 10)
                    ->first();
            }
            if ($school) {
                $schools[] = $school;
            }
        }

        if (count($schools) < 5) {
            // Get random schools if we don't have enough
            $schools = School::inRandomOrder()->limit(10)->get();
        }

        echo "Using " . count($schools) . " schools for demo data\n";

        // Create realistic student distribution
        $totalStudents = 1000; // Create 1000 students for better visualization
        $studentsCreated = 0;

        // Realistic grade distribution
        $gradeDistribution = [
            1 => 0.18,  // 18% Grade 1
            2 => 0.17,  // 17% Grade 2
            3 => 0.17,  // 17% Grade 3
            4 => 0.16,  // 16% Grade 4
            5 => 0.16,  // 16% Grade 5
            6 => 0.16,  // 16% Grade 6
        ];

        // Get or create a default teacher
        $teacher = User::where('role', 'teacher')->first();
        if (!$teacher) {
            $teacher = User::where('email', 'teacher1@prathaminternational.org')->first();
        }

        foreach ($schools as $school) {
            $studentsPerSchool = intval($totalStudents / count($schools));
            
            for ($i = 0; $i < $studentsPerSchool; $i++) {
                // Determine grade based on distribution
                $rand = mt_rand(1, 100) / 100;
                $cumulative = 0;
                $grade = 1;
                foreach ($gradeDistribution as $g => $prob) {
                    $cumulative += $prob;
                    if ($rand <= $cumulative) {
                        $grade = $g;
                        break;
                    }
                }

                $student = Student::create([
                    'name' => 'Student ' . ($studentsCreated + 1001), // Start from 1001 to avoid conflicts
                    'student_code' => 'PUB' . str_pad($studentsCreated + 1001, 6, '0', STR_PAD_LEFT),
                    'birthdate' => now()->subYears(6 + $grade)->subDays(rand(0, 365)),
                    'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                    'school_id' => $school->id, // Use the accessor that returns sclAutoID
                    'teacher_id' => $teacher ? $teacher->id : null,
                    'grade' => $grade,
                    'age' => 6 + $grade,
                    'parent_name' => 'Guardian ' . ($studentsCreated + 1001),
                    'parent_phone' => '0' . rand(10, 99) . rand(100000, 999999),
                    'address' => 'Address ' . ($studentsCreated + 1001),
                    'status' => 'active',
                ]);

                // Create assessments with realistic TaRL progression
                $this->createRealisticAssessments($student, $grade);
                
                $studentsCreated++;
            }
        }

        echo "Created $studentsCreated students with assessments\n";

        // Clear any cache
        \Artisan::call('cache:clear');
    }

    /**
     * Create realistic assessment progression for a student
     */
    private function createRealisticAssessments($student, $grade)
    {
        // Khmer levels progression
        $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        
        // Math levels progression
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

        // Realistic starting points based on grade
        $khmerStartIndex = $this->getStartingLevel($grade, 'khmer');
        $mathStartIndex = $this->getStartingLevel($grade, 'math');

        // Baseline Assessment
        $khmerBaseline = $khmerLevels[min($khmerStartIndex, count($khmerLevels) - 1)];
        $mathBaseline = $mathLevels[min($mathStartIndex, count($mathLevels) - 1)];

        Assessment::create([
            'student_id' => $student->id,
            'cycle' => 'baseline',
            'subject' => 'khmer',
            'level' => $khmerBaseline,
            'score' => rand(40, 70), // Baseline scores typically lower
            'assessed_at' => now()->subMonths(6),
            'assessed_by' => 1,
        ]);

        Assessment::create([
            'student_id' => $student->id,
            'cycle' => 'baseline',
            'subject' => 'math',
            'level' => $mathBaseline,
            'score' => rand(40, 70),
            'assessed_at' => now()->subMonths(6),
            'assessed_by' => 1,
        ]);

        // Midline Assessment - 70% of students show improvement
        if (rand(1, 100) <= 85) { // 85% participation rate
            $khmerMidlineIndex = (rand(1, 100) <= 70) ? min($khmerStartIndex + 1, count($khmerLevels) - 1) : $khmerStartIndex;
            $mathMidlineIndex = (rand(1, 100) <= 70) ? min($mathStartIndex + 1, count($mathLevels) - 1) : $mathStartIndex;

            Assessment::create([
                'student_id' => $student->id,
                'cycle' => 'midline',
                'subject' => 'khmer',
                'level' => $khmerLevels[$khmerMidlineIndex],
                'score' => rand(50, 80), // Improved scores
                'assessed_at' => now()->subMonths(3),
                'assessed_by' => 1,
            ]);

            Assessment::create([
                'student_id' => $student->id,
                'cycle' => 'midline',
                'subject' => 'math',
                'level' => $mathLevels[$mathMidlineIndex],
                'score' => rand(50, 80),
                'assessed_at' => now()->subMonths(3),
                'assessed_by' => 1,
            ]);

            // Endline Assessment - 80% of midline students continue
            if (rand(1, 100) <= 80) {
                $khmerEndlineIndex = (rand(1, 100) <= 75) ? min($khmerMidlineIndex + 1, count($khmerLevels) - 1) : $khmerMidlineIndex;
                $mathEndlineIndex = (rand(1, 100) <= 75) ? min($mathMidlineIndex + 1, count($mathLevels) - 1) : $mathMidlineIndex;

                Assessment::create([
                    'student_id' => $student->id,
                    'cycle' => 'endline',
                    'subject' => 'khmer',
                    'level' => $khmerLevels[$khmerEndlineIndex],
                    'score' => rand(60, 95), // Best scores at endline
                    'assessed_at' => now()->subDays(rand(1, 30)),
                    'assessed_by' => 1,
                ]);

                Assessment::create([
                    'student_id' => $student->id,
                    'cycle' => 'endline',
                    'subject' => 'math',
                    'level' => $mathLevels[$mathEndlineIndex],
                    'score' => rand(60, 95),
                    'assessed_at' => now()->subDays(rand(1, 30)),
                    'assessed_by' => 1,
                ]);
            }
        }
    }

    /**
     * Get realistic starting level based on grade
     */
    private function getStartingLevel($grade, $subject)
    {
        if ($subject === 'khmer') {
            // Khmer reading levels by grade
            switch ($grade) {
                case 1:
                    return rand(0, 1); // Beginner to Letter
                case 2:
                    return rand(1, 2); // Letter to Word
                case 3:
                    return rand(2, 3); // Word to Paragraph
                case 4:
                    return rand(3, 4); // Paragraph to Story
                case 5:
                    return rand(4, 5); // Story to Comp. 1
                case 6:
                default:
                    return rand(5, 6); // Comp. 1 to Comp. 2
            }
        } else {
            // Math levels by grade
            switch ($grade) {
                case 1:
                    return rand(0, 1); // Beginner to 1-Digit
                case 2:
                    return rand(1, 2); // 1-Digit to 2-Digit
                case 3:
                    return rand(2, 3); // 2-Digit to Subtraction
                case 4:
                    return rand(3, 4); // Subtraction to Division
                case 5:
                case 6:
                default:
                    return rand(4, 5); // Division to Word Problem
            }
        }
    }
}