<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ComprehensiveAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing assessments and histories
        if (\DB::getDriverName() === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Assessment::truncate();
            \DB::table('assessment_histories')->truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            // For PostgreSQL, use CASCADE
            \DB::statement('TRUNCATE TABLE assessments CASCADE;');
            \DB::statement('TRUNCATE TABLE assessment_histories CASCADE;');
        }

        // Define assessment levels
        $khmerLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp 1', 'Comp 2'];
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

        // Get all students
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please run student seeder first.');

            return;
        }

        $this->command->info('Creating assessments for '.$students->count().' students...');

        foreach ($students as $index => $student) {
            // Define assessment dates (spread across academic year)
            $baselineDate = Carbon::now()->subMonths(8)->addDays(rand(0, 14)); // Start of academic year
            $midlineDate = Carbon::now()->subMonths(4)->addDays(rand(0, 14));   // Mid academic year
            $endlineDate = Carbon::now()->subDays(rand(0, 14));                 // End of academic year

            // Create baseline assessments
            $this->createAssessment($student, 'baseline', 'khmer', $khmerLevels, $baselineDate, $index);
            $this->createAssessment($student, 'baseline', 'math', $mathLevels, $baselineDate, $index);

            // Create midline assessments (with some progression)
            $this->createAssessment($student, 'midline', 'khmer', $khmerLevels, $midlineDate, $index, 1);
            $this->createAssessment($student, 'midline', 'math', $mathLevels, $midlineDate, $index, 1);

            // Create endline assessments (with more progression)
            $this->createAssessment($student, 'endline', 'khmer', $khmerLevels, $endlineDate, $index, 2);
            $this->createAssessment($student, 'endline', 'math', $mathLevels, $endlineDate, $index, 2);
        }

        $totalAssessments = Assessment::count();
        $this->command->info("Successfully created {$totalAssessments} assessments!");

        // Display summary
        $this->displaySummary();
    }

    /**
     * Create an assessment for a student
     */
    private function createAssessment($student, $cycle, $subject, $levels, $date, $studentIndex, $progression = 0)
    {
        // Determine initial level based on student index and grade
        $baseLevel = $this->getBaseLevel($student, $subject, $studentIndex);

        // Apply progression for midline and endline
        $levelIndex = min($baseLevel + $progression, count($levels) - 1);
        $level = $levels[$levelIndex];

        // Generate score based on level and cycle
        $score = $this->generateScore($cycle, $levelIndex, count($levels));

        Assessment::create([
            'student_id' => $student->id,
            'cycle' => $cycle,
            'subject' => $subject,
            'level' => $level,
            'score' => $score,
            'assessed_at' => $date,
        ]);
    }

    /**
     * Determine base level for a student
     */
    private function getBaseLevel($student, $subject, $studentIndex)
    {
        // Use a mix of student grade and index to create variety
        $gradeBonus = $student->grade == 5 ? 1 : 0;

        if ($subject === 'khmer') {
            // Distribute Khmer levels: more beginners, fewer advanced
            $distribution = [0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 3, 3, 4, 4, 5, 6];

            return $distribution[$studentIndex % count($distribution)] + $gradeBonus;
        } else {
            // Math levels: similar distribution
            $distribution = [0, 0, 1, 1, 1, 2, 2, 2, 3, 3, 4, 4, 5];

            return min($distribution[$studentIndex % count($distribution)] + $gradeBonus, 5);
        }
    }

    /**
     * Generate realistic scores based on cycle and level
     */
    private function generateScore($cycle, $levelIndex, $maxLevels)
    {
        // Base score depends on how advanced the level is
        $levelPercentage = ($levelIndex / ($maxLevels - 1)) * 100;

        // Scores improve through cycles
        $cycleBonus = match ($cycle) {
            'baseline' => 0,
            'midline' => 10,
            'endline' => 20,
            default => 0
        };

        // Generate score with some randomness
        $baseScore = 40 + ($levelPercentage * 0.4) + $cycleBonus;
        $randomVariation = rand(-10, 10);

        return max(0, min(100, round($baseScore + $randomVariation, 1)));
    }

    /**
     * Display summary of created assessments
     */
    private function displaySummary()
    {
        $this->command->info("\nAssessment Summary:");
        $this->command->info('==================');

        // Summary by cycle
        $this->command->info("\nBy Cycle:");
        foreach (['baseline', 'midline', 'endline'] as $cycle) {
            $count = Assessment::where('cycle', $cycle)->count();
            $avgScore = Assessment::where('cycle', $cycle)->avg('score');
            $this->command->info("  {$cycle}: {$count} assessments, Avg Score: ".round($avgScore, 1));
        }

        // Summary by subject
        $this->command->info("\nBy Subject:");
        foreach (['khmer', 'math'] as $subject) {
            $count = Assessment::where('subject', $subject)->count();
            $avgScore = Assessment::where('subject', $subject)->avg('score');
            $this->command->info("  {$subject}: {$count} assessments, Avg Score: ".round($avgScore, 1));
        }

        // Level distribution for Khmer
        $this->command->info("\nKhmer Level Distribution:");
        if (\DB::getDriverName() === 'mysql') {
            $khmerLevels = Assessment::where('subject', 'khmer')
                ->selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->orderByRaw("FIELD(level, 'Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp 1', 'Comp 2')")
                ->pluck('count', 'level');
        } else {
            // PostgreSQL version using CASE
            $khmerLevels = Assessment::where('subject', 'khmer')
                ->selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->orderByRaw("CASE 
                    WHEN level = 'Beginner' THEN 1
                    WHEN level = 'Letter' THEN 2
                    WHEN level = 'Word' THEN 3
                    WHEN level = 'Paragraph' THEN 4
                    WHEN level = 'Story' THEN 5
                    WHEN level = 'Comp 1' THEN 6
                    WHEN level = 'Comp 2' THEN 7
                    ELSE 8 END")
                ->pluck('count', 'level');
        }

        foreach ($khmerLevels as $level => $count) {
            $this->command->info("  {$level}: {$count}");
        }

        // Level distribution for Math
        $this->command->info("\nMath Level Distribution:");
        if (\DB::getDriverName() === 'mysql') {
            $mathLevels = Assessment::where('subject', 'math')
                ->selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->orderByRaw("FIELD(level, 'Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem')")
                ->pluck('count', 'level');
        } else {
            // PostgreSQL version using CASE
            $mathLevels = Assessment::where('subject', 'math')
                ->selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->orderByRaw("CASE 
                    WHEN level = 'Beginner' THEN 1
                    WHEN level = '1-Digit' THEN 2
                    WHEN level = '2-Digit' THEN 3
                    WHEN level = 'Subtraction' THEN 4
                    WHEN level = 'Division' THEN 5
                    WHEN level = 'Word Problem' THEN 6
                    ELSE 7 END")
                ->pluck('count', 'level');
        }

        foreach ($mathLevels as $level => $count) {
            $this->command->info("  {$level}: {$count}");
        }
    }
}
