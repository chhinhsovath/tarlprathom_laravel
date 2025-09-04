<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompleteRemainingAssessmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Target schools that have insufficient assessments
        $schoolsNeedingAssessments = [
            'ទួលវិហារ', 'រាយប៉ាយ', 'ទួលបី', 'សាខា១', 'អូរស្វាយ', 
            'ព្រែកកុយ', 'សាខា២', 'រការអារ', 'អន្ទង់ស'
        ];
        
        $cycles = ['baseline', 'midline', 'endline'];
        $subjects = ['khmer', 'math'];
        
        // Define assessment levels for each subject
        $khmerLevels = ['Nothing', 'Letters', 'Words', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        $mathLevels = ['Nothing', 'Numbers', 'Addition', 'Subtraction', 'Multiplication', 'Division', 'Word Problems'];

        $this->command->info("Completing assessments for remaining schools...");

        foreach ($schoolsNeedingAssessments as $schoolName) {
            $school = PilotSchool::where('school_name', $schoolName)->first();
            
            if (!$school) {
                $this->command->warn("School not found: {$schoolName}");
                continue;
            }

            $this->command->info("Processing school: {$school->school_name}");
            
            $students = Student::where('pilot_school_id', $school->id)->get();
            $teachers = User::where('pilot_school_id', $school->id)->where('role', 'teacher')->get();
            
            if ($teachers->isEmpty()) {
                $this->command->warn("  No teachers found for school {$school->school_name}");
                continue;
            }

            $createdCount = 0;
            
            foreach ($students as $student) {
                // Get a random teacher from this school
                $teacher = $teachers->random();
                
                foreach ($cycles as $cycleIndex => $cycle) {
                    foreach ($subjects as $subject) {
                        // Check if assessment already exists
                        $existingAssessment = Assessment::where([
                            'student_id' => $student->id,
                            'cycle' => $cycle,
                            'subject' => $subject
                        ])->first();
                        
                        if ($existingAssessment) {
                            continue;
                        }

                        // Determine assessment level based on student progress
                        $levels = $subject === 'khmer' ? $khmerLevels : $mathLevels;
                        
                        // Simulate student improvement over time
                        $baseLevel = rand(0, 2); // Start low
                        $improvement = $cycleIndex * rand(0, 2); // Improve over cycles
                        $levelIndex = min($baseLevel + $improvement, count($levels) - 1);
                        $level = $levels[$levelIndex];
                        
                        // Calculate score based on level
                        $score = $this->calculateScore($level, $subject);
                        
                        // Create assessment date based on cycle
                        $assessedAt = $this->getAssessmentDate($cycle);
                        
                        Assessment::create([
                            'student_id' => $student->id,
                            'assessor_id' => $teacher->id,
                            'pilot_school_id' => $school->id,
                            'cycle' => $cycle,
                            'subject' => $subject,
                            'level' => $level,
                            'score' => $score,
                            'assessed_at' => $assessedAt,
                        ]);
                        
                        $createdCount++;
                    }
                }
            }
            
            $this->command->info("  Created {$createdCount} assessments for {$students->count()} students");
        }

        $this->command->info("Remaining assessments completed!");
    }

    /**
     * Calculate score based on assessment level
     */
    private function calculateScore(string $level, string $subject): float
    {
        $scoreMap = [
            // Khmer levels
            'Nothing' => rand(0, 10),
            'Letters' => rand(10, 25),
            'Words' => rand(25, 40),
            'Paragraph' => rand(40, 60),
            'Story' => rand(60, 75),
            'Comp. 1' => rand(75, 90),
            'Comp. 2' => rand(90, 100),
            
            // Math levels
            'Numbers' => rand(10, 25),
            'Addition' => rand(25, 40),
            'Subtraction' => rand(40, 55),
            'Multiplication' => rand(55, 70),
            'Division' => rand(70, 85),
            'Word Problems' => rand(85, 100),
        ];

        return $scoreMap[$level] ?? rand(0, 100);
    }

    /**
     * Get assessment date based on cycle
     */
    private function getAssessmentDate(string $cycle): \DateTime
    {
        $baseDate = now();
        
        switch ($cycle) {
            case 'baseline':
                return $baseDate->copy()->subMonths(rand(6, 12));
            case 'midline':
                return $baseDate->copy()->subMonths(rand(3, 6));
            case 'endline':
                return $baseDate->copy()->subMonths(rand(0, 3));
            default:
                return $baseDate;
        }
    }
}