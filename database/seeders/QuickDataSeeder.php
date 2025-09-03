<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class QuickDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Quick data seeding started...');
        
        // Ensure admin exists
        $adminExists = DB::table('users')->where('email', 'kairav@prathaminternational.org')->exists();
        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Kairav Pratham',
                'email' => 'kairav@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Get pilot schools with corresponding school record
        $schools = DB::table('pilot_schools')->take(5)->get();
        
        if ($schools->isEmpty()) {
            $this->command->error('No pilot schools found!');
            return;
        }
        
        $this->command->info('Creating students for ' . $schools->count() . ' schools...');
        
        foreach ($schools as $pilotSchool) {
            // Find corresponding school record
            $schoolId = DB::table('schools')
                ->where('name', $pilotSchool->school_name)
                ->value('id');
            
            if (!$schoolId) {
                // Create school record if missing
                $schoolId = DB::table('schools')->insertGetId([
                    'name' => $pilotSchool->school_name,
                    'code' => $pilotSchool->school_code ?? 'SCH' . $pilotSchool->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Create 30 students per school
            for ($i = 1; $i <= 30; $i++) {
                $grade = rand(4, 6); // TaRL project focuses on grades 4-6
                $age = $grade + 5; // Grade 4=9yrs, Grade 5=10yrs, Grade 6=11yrs  
                $gender = rand(0, 1) ? 'male' : 'female';
                $studentId = DB::table('students')->insertGetId([
                    'student_code' => 'STU' . $pilotSchool->id . '_' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'name' => 'Student ' . $i . ' School ' . $pilotSchool->id,
                    'enrollment_status' => 'active',
                    'pilot_school_id' => $pilotSchool->id,
                    'school_id' => $schoolId, // Use the correct school_id
                    'previous_year_grade' => $grade - 1,
                    'class' => 'Grade ' . $grade, // Required field - Grades 4, 5, 6
                    'age' => $age, // Required field - Ages 9, 10, 11
                    'sex' => $gender, // Required field - must be 'male' or 'female'
                    'date_of_birth' => Carbon::now()->subYears($age)->format('Y-m-d'),
                    'gender' => $gender,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Create assessments for each student
                $cycles = ['baseline', 'midline', 'endline'];
                $subjects = ['khmer', 'math'];
                
                foreach ($cycles as $cycleIndex => $cycle) {
                    foreach ($subjects as $subject) {
                        // Progressive scores
                        $baseScore = rand(30, 50);
                        $improvement = $cycleIndex * rand(10, 20);
                        $score = min(95, $baseScore + $improvement);
                        
                        DB::table('assessments')->insert([
                            'student_id' => $studentId,
                            'cycle' => $cycle,
                            'subject' => $subject,
                            'level' => $this->getLevel($score, $subject),
                            'score' => $score,
                            'assessed_at' => $this->getAssessmentDate($cycle),
                            'pilot_school_id' => $pilotSchool->id,
                            'percentage_score' => $score,
                            'performance_level' => $this->getPerformanceLevel($score),
                            'completed_assessment' => true,
                            'requires_intervention' => $score < 50,
                            'parent_informed' => false,
                            'used_assistance' => false,
                            'reassessment_needed' => $score < 40,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
            
            $this->command->info('Created 30 students and assessments for school ID ' . $pilotSchool->id);
        }
        
        // Create some mentoring visits
        $this->command->info('Creating mentoring visits...');
        
        foreach ($schools as $school) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('mentoring_visits')->insert([
                    'pilot_school_id' => $school->id,
                    'visit_date' => Carbon::now()->subDays(rand(1, 30)),
                    'visit_type' => 'observation',
                    'teachers_present' => rand(2, 5),
                    'students_assessed' => rand(10, 20),
                    'focus_area' => 'Teaching methodology',
                    'strengths_observed' => 'Good classroom management and student engagement',
                    'areas_for_improvement' => 'Need more interactive activities',
                    'support_provided' => 'Training materials and guidance provided',
                    'next_steps' => 'Follow-up visit scheduled',
                    'follow_up_required' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        $this->command->info('Quick data seeding completed!');
    }
    
    protected function getLevel($score, $subject)
    {
        if ($subject === 'khmer') {
            if ($score >= 80) return 'paragraph';
            if ($score >= 60) return 'sentence';
            if ($score >= 40) return 'word';
            return 'letter';
        } else {
            if ($score >= 80) return 'division';
            if ($score >= 60) return 'multiplication';
            if ($score >= 40) return 'subtraction';
            return 'number_recognition';
        }
    }
    
    protected function getPerformanceLevel($score)
    {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'satisfactory';
        if ($score >= 40) return 'needs_improvement';
        return 'poor';
    }
    
    protected function getAssessmentDate($cycle)
    {
        switch ($cycle) {
            case 'baseline':
                return Carbon::now()->subMonths(3)->format('Y-m-d');
            case 'midline':
                return Carbon::now()->subMonths(1)->format('Y-m-d');
            case 'endline':
                return Carbon::now()->subDays(7)->format('Y-m-d');
            default:
                return Carbon::now()->format('Y-m-d');
        }
    }
}