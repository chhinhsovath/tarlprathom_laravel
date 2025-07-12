<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Student;
use Illuminate\Database\Seeder;

class BaselineAssessmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();

        // Assessment levels
        $khmerLevels = ['Beginner', 'Letter Reader', 'Word Level', 'Paragraph Reader', 'Story Reader', 'Comp. 1', 'Comp. 2'];
        $mathLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

        foreach ($students as $index => $student) {
            // Create baseline assessment for Khmer
            // Assign levels based on index to have variety
            $levelIndex = $index % count($khmerLevels);
            $level = $khmerLevels[$levelIndex];

            Assessment::create([
                'student_id' => $student->id,
                'subject' => 'khmer',
                'cycle' => 'baseline',
                'level' => $level,
                'score' => rand(0, 100),
                'assessed_at' => now()->subDays(rand(1, 30)),
            ]);

            // Also create baseline for Math
            $mathLevelIndex = $index % count($mathLevels);
            Assessment::create([
                'student_id' => $student->id,
                'subject' => 'math',
                'cycle' => 'baseline',
                'level' => $mathLevels[$mathLevelIndex],
                'score' => rand(0, 100),
                'assessed_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        echo 'Created baseline assessments for '.$students->count()." students.\n";
    }
}
