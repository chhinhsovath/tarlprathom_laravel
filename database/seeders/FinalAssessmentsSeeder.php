<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinalAssessmentsSeeder extends Seeder
{
    public function run(): void
    {
        $remainingSchools = ['រការអារ', 'អន្ទង់ស'];
        $cycles = ['baseline', 'midline', 'endline'];
        $subjects = ['khmer', 'math'];
        
        foreach ($remainingSchools as $schoolName) {
            $school = PilotSchool::where('school_name', $schoolName)->first();
            if (!$school) continue;
            
            $students = Student::where('pilot_school_id', $school->id)->get();
            $teacher = User::where('pilot_school_id', $school->id)->where('role', 'teacher')->first();
            
            if (!$teacher) continue;
            
            $this->command->info("Processing {$schoolName}: {$students->count()} students");
            
            foreach ($students as $student) {
                foreach ($cycles as $cycle) {
                    foreach ($subjects as $subject) {
                        if (Assessment::where(['student_id' => $student->id, 'cycle' => $cycle, 'subject' => $subject])->exists()) {
                            continue;
                        }
                        
                        $level = $subject === 'khmer' ? 'Words' : 'Addition';
                        $score = rand(25, 60);
                        $assessedAt = now()->subMonths($cycle === 'baseline' ? 8 : ($cycle === 'midline' ? 4 : 1));
                        
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
                    }
                }
            }
            
            $this->command->info("Completed {$schoolName}");
        }
        
        $this->command->info("All assessments completed!");
    }
}