<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ComprehensiveTaRLSeeder extends Seeder
{
    protected $faker;
    
    public function __construct()
    {
        $this->faker = Faker::create();
    }
    
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::beginTransaction();
        
        try {
            $this->command->info('Starting comprehensive TaRL data seeding...');
            
            // 1. Create Admin Users
            $this->createAdminUsers();
            
            // 2. Create Coordinators
            $coordinators = $this->createCoordinators();
            
            // 3. Create Mentors with school assignments
            $mentors = $this->createMentors();
            
            // 4. Create Teachers for each school
            $teachers = $this->createTeachers();
            
            // 5. Create Students for each school
            $students = $this->createStudents();
            
            // 6. Create Assessments (Baseline, Midline, Endline)
            $this->createAssessments($students);
            
            // 7. Create Mentoring Visits
            $this->createMentoringVisits($mentors);
            
            // 8. Create Learning Materials
            $this->createLearningMaterials();
            
            // 9. Create Teaching Activities
            $this->createTeachingActivities($teachers);
            
            // 10. Create Progress Tracking
            $this->createProgressTracking($students);
            
            DB::commit();
            $this->command->info('✅ Comprehensive TaRL seeding completed successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create admin users
     */
    protected function createAdminUsers()
    {
        $this->command->info('Creating admin users...');
        
        $admins = [
            [
                'name' => 'System Admin',
                'email' => 'admin@tarl.edu.kh',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '012345678',
                'is_active' => true,
            ],
            [
                'name' => 'Kairav Admin',
                'email' => 'kairav@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '012345679',
                'is_active' => true,
            ]
        ];
        
        foreach ($admins as $admin) {
            DB::table('users')->updateOrInsert(
                ['email' => $admin['email']],
                array_merge($admin, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }
    
    /**
     * Create coordinators
     */
    protected function createCoordinators()
    {
        $this->command->info('Creating coordinators...');
        
        $coordinators = [];
        $provinces = ['Battambang', 'Kampongcham'];
        
        foreach ($provinces as $index => $province) {
            $coordinator = [
                'name' => "Provincial Coordinator - $province",
                'email' => strtolower($province) . '.coordinator@tarl.edu.kh',
                'password' => Hash::make('coord123'),
                'role' => 'coordinator',
                'province' => $province,
                'phone' => '01234568' . $index,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $coordinatorId = DB::table('users')->insertGetId($coordinator);
            $coordinators[$province] = $coordinatorId;
        }
        
        return $coordinators;
    }
    
    /**
     * Create mentors with school assignments
     */
    protected function createMentors()
    {
        $this->command->info('Creating mentors with school assignments...');
        
        $mentors = [];
        $schools = DB::table('pilot_schools')->get();
        $schoolsByProvince = $schools->groupBy('province');
        
        $mentorIndex = 0;
        foreach ($schoolsByProvince as $province => $provinceSchools) {
            // Create 3 mentors per province
            for ($i = 1; $i <= 3; $i++) {
                $mentor = [
                    'name' => "Mentor $i - $province",
                    'email' => strtolower($province) . ".mentor$i@tarl.edu.kh",
                    'password' => Hash::make('mentor123'),
                    'role' => 'mentor',
                    'province' => $province,
                    'phone' => '0123457' . $mentorIndex,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $mentorId = DB::table('users')->insertGetId($mentor);
                $mentors[] = $mentorId;
                
                // Assign 5 schools to each mentor
                $assignedSchools = $provinceSchools->slice(($i - 1) * 5, 5);
                foreach ($assignedSchools as $school) {
                    DB::table('mentor_school')->insert([
                        'user_id' => $mentorId,
                        'school_id' => $school->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                $mentorIndex++;
            }
        }
        
        return $mentors;
    }
    
    /**
     * Create teachers for each school
     */
    protected function createTeachers()
    {
        $this->command->info('Creating teachers for each school...');
        
        $teachers = [];
        $schools = DB::table('pilot_schools')->get();
        
        foreach ($schools as $school) {
            // Create 3-5 teachers per school
            $numTeachers = rand(3, 5);
            
            for ($i = 1; $i <= $numTeachers; $i++) {
                $teacher = [
                    'name' => $this->generateKhmerName() . " (Teacher $i)",
                    'email' => "school{$school->id}.teacher$i@tarl.edu.kh",
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'pilot_school_id' => $school->id,
                    'province' => $school->province,
                    'district' => $school->district,
                    'phone' => '01234' . str_pad($school->id, 3, '0', STR_PAD_LEFT) . $i,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $teacherId = DB::table('users')->insertGetId($teacher);
                $teachers[$school->id][] = $teacherId;
            }
        }
        
        return $teachers;
    }
    
    /**
     * Create students for each school
     */
    protected function createStudents()
    {
        $this->command->info('Creating students for each school...');
        
        $students = [];
        $schools = DB::table('pilot_schools')->get();
        
        foreach ($schools as $school) {
            // Create 30-50 students per school
            $numStudents = rand(30, 50);
            
            for ($i = 1; $i <= $numStudents; $i++) {
                $grade = rand(1, 6);
                $gender = $this->faker->randomElement(['male', 'female']);
                $dob = Carbon::now()->subYears($grade + 6)->subDays(rand(0, 365));
                
                // Find matching school in schools table
                $schoolRecord = DB::table('schools')
                    ->where('school_code', $school->school_code)
                    ->first();
                
                $student = [
                    'student_code' => 'STU' . $school->id . '_' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'name' => $this->generateKhmerName() . ' ' . $i, // Add number to ensure uniqueness
                    'gender' => $gender,
                    'sex' => $gender, // Keep both for compatibility
                    'date_of_birth' => $dob,
                    'age' => $dob->age,
                    'previous_year_grade' => $grade - 1,
                    'class' => $grade . $this->faker->randomElement(['A', 'B', 'C']),
                    'school_id' => $schoolRecord ? $schoolRecord->id : 1, // Use matching school or default
                    'pilot_school_id' => $school->id,
                    'guardian_name' => $this->generateKhmerName() . ' (អាណាព្យាបាល)',
                    'guardian_phone' => '0' . rand(10000000, 99999999),
                    'home_address' => 'ភូមិ ' . rand(1, 10) . ', ' . $school->cluster . ', ' . $school->district,
                    'home_village' => 'ភូមិ ' . $this->faker->randomElement(['សុខសាន្ត', 'សន្តិភាព', 'សាមគ្គី']),
                    'home_commune' => $school->cluster,
                    'home_district' => $school->district,
                    'home_province' => $school->province,
                    'enrollment_date' => Carbon::now()->subMonths(rand(1, 12)),
                    'enrollment_status' => $this->faker->randomElement(['active', 'active', 'active', 'dropped_out']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $studentId = DB::table('students')->insertGetId($student);
                $students[] = $studentId;
            }
        }
        
        return $students;
    }
    
    /**
     * Create assessments for students
     */
    protected function createAssessments($studentIds)
    {
        $this->command->info('Creating student assessments...');
        
        $assessmentTypes = ['baseline', 'midline', 'endline'];
        $subjects = ['khmer', 'math'];
        $levels = ['letter', 'word', 'sentence', 'paragraph', 'story'];
        $mathLevels = ['1-digit', '2-digit', 'subtraction', 'word_problem', 'division'];
        
        // Get a sample of students for assessment (70% participation rate)
        $assessedStudents = DB::table('students')
            ->where('enrollment_status', 'active')
            ->inRandomOrder()
            ->limit(count($studentIds) * 0.7)
            ->get();
        
        foreach ($assessedStudents as $student) {
            foreach ($assessmentTypes as $typeIndex => $type) {
                foreach ($subjects as $subject) {
                    // Calculate progressive improvement
                    $baseScore = rand(30, 60);
                    $improvement = $typeIndex * rand(10, 20);
                    $score = min(100, $baseScore + $improvement);
                    
                    $level = $subject === 'khmer' 
                        ? $levels[min(floor($score / 20), 4)]
                        : $mathLevels[min(floor($score / 20), 4)];
                    
                    $assessment = [
                        'student_id' => $student->id,
                        'cycle' => $type, // Changed from assessment_type to cycle
                        'subject' => $subject,
                        'level' => $level,
                        'score' => $score,
                        'assessed_at' => $this->getAssessmentDate($type), // Changed from assessment_date to assessed_at
                        'assessor_id' => DB::table('users')
                            ->where('pilot_school_id', $student->pilot_school_id)
                            ->where('role', 'teacher')
                            ->inRandomOrder()
                            ->value('id'),
                        'pilot_school_id' => $student->pilot_school_id,
                        'percentage_score' => $score,
                        'performance_level' => $this->getPerformanceLevel($score),
                        'assessor_comments' => $this->generateAssessmentNote($score),
                        'completed_assessment' => true,
                        'requires_intervention' => $score < 50,
                        'parent_informed' => false,
                        'used_assistance' => false,
                        'reassessment_needed' => $score < 40,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    DB::table('assessments')->insert($assessment);
                }
            }
        }
    }
    
    /**
     * Create mentoring visits
     */
    protected function createMentoringVisits($mentorIds)
    {
        $this->command->info('Creating mentoring visits...');
        
        $visitTypes = ['observation', 'coaching', 'training', 'evaluation'];
        $focusAreas = [
            'Teaching methodology',
            'Classroom management',
            'Student engagement',
            'Assessment techniques',
            'Learning materials usage'
        ];
        
        foreach ($mentorIds as $mentorId) {
            // Get schools assigned to this mentor
            $assignedSchools = DB::table('mentor_school')
                ->where('user_id', $mentorId)
                ->pluck('school_id');
            
            foreach ($assignedSchools as $schoolId) {
                // Create 3-5 visits per school
                $numVisits = rand(3, 5);
                
                for ($i = 0; $i < $numVisits; $i++) {
                    $visitDate = Carbon::now()->subDays(rand(1, 90));
                    
                    $visit = [
                        'pilot_school_id' => $schoolId,
                        'mentor_id' => $mentorId,
                        'visit_date' => $visitDate,
                        'visit_type' => $this->faker->randomElement($visitTypes),
                        'teachers_present' => rand(2, 5),
                        'students_assessed' => rand(10, 30),
                        'focus_area' => $this->faker->randomElement($focusAreas),
                        'strengths_observed' => $this->generateStrengths(),
                        'areas_for_improvement' => $this->generateImprovements(),
                        'support_provided' => $this->generateSupport(),
                        'next_steps' => $this->generateNextSteps(),
                        'follow_up_required' => $this->faker->boolean(30),
                        'created_at' => $visitDate,
                        'updated_at' => $visitDate
                    ];
                    
                    DB::table('mentoring_visits')->insert($visit);
                }
            }
        }
    }
    
    /**
     * Create learning materials
     */
    protected function createLearningMaterials()
    {
        $this->command->info('Creating learning materials...');
        
        $materials = [
            ['name' => 'Khmer Alphabet Cards', 'type' => 'flashcards', 'subject' => 'khmer', 'level' => 'beginner'],
            ['name' => 'Number Recognition Cards', 'type' => 'flashcards', 'subject' => 'math', 'level' => 'beginner'],
            ['name' => 'Word Building Blocks', 'type' => 'manipulatives', 'subject' => 'khmer', 'level' => 'intermediate'],
            ['name' => 'Counting Beads', 'type' => 'manipulatives', 'subject' => 'math', 'level' => 'beginner'],
            ['name' => 'Story Books Level 1', 'type' => 'books', 'subject' => 'khmer', 'level' => 'intermediate'],
            ['name' => 'Story Books Level 2', 'type' => 'books', 'subject' => 'khmer', 'level' => 'advanced'],
            ['name' => 'Math Problem Worksheets', 'type' => 'worksheets', 'subject' => 'math', 'level' => 'intermediate'],
            ['name' => 'Writing Practice Sheets', 'type' => 'worksheets', 'subject' => 'khmer', 'level' => 'beginner']
        ];
        
        foreach ($materials as $material) {
            DB::table('learning_materials')->insert(array_merge($material, [
                'description' => 'Educational material for ' . $material['subject'] . ' learning',
                'quantity' => rand(20, 100),
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
    
    /**
     * Create teaching activities
     */
    protected function createTeachingActivities($teachersBySchool)
    {
        $this->command->info('Creating teaching activities...');
        
        $activities = [
            'Letter recognition game',
            'Number counting exercise',
            'Story reading session',
            'Word formation activity',
            'Math problem solving',
            'Group reading practice',
            'Writing practice',
            'Peer learning activity'
        ];
        
        foreach ($teachersBySchool as $schoolId => $teachers) {
            foreach ($teachers as $teacherId) {
                // Create 5-10 activities per teacher
                $numActivities = rand(5, 10);
                
                for ($i = 0; $i < $numActivities; $i++) {
                    $activityDate = Carbon::now()->subDays(rand(1, 30));
                    
                    DB::table('teaching_activities')->insert([
                        'teacher_id' => $teacherId,
                        'pilot_school_id' => $schoolId,
                        'activity_name' => $this->faker->randomElement($activities),
                        'activity_date' => $activityDate,
                        'duration_minutes' => rand(30, 90),
                        'students_participated' => rand(15, 30),
                        'materials_used' => 'Various learning materials',
                        'outcomes' => $this->generateActivityOutcome(),
                        'created_at' => $activityDate,
                        'updated_at' => $activityDate
                    ]);
                }
            }
        }
    }
    
    /**
     * Create progress tracking
     */
    protected function createProgressTracking($studentIds)
    {
        $this->command->info('Creating student progress tracking...');
        
        $sampleStudents = DB::table('students')
            ->whereIn('id', $studentIds)
            ->where('enrollment_status', 'active')
            ->limit(100)
            ->get();
        
        foreach ($sampleStudents as $student) {
            // Create monthly progress records
            for ($month = 3; $month >= 0; $month--) {
                $trackingDate = Carbon::now()->subMonths($month);
                
                DB::table('progress_tracking')->insert([
                    'student_id' => $student->id,
                    'tracking_date' => $trackingDate,
                    'attendance_percentage' => rand(70, 100),
                    'khmer_level' => $this->faker->randomElement(['letter', 'word', 'sentence', 'paragraph']),
                    'math_level' => $this->faker->randomElement(['1-digit', '2-digit', 'subtraction', 'word_problem']),
                    'teacher_notes' => $this->generateProgressNote(),
                    'created_at' => $trackingDate,
                    'updated_at' => $trackingDate
                ]);
            }
        }
    }
    
    /**
     * Helper function to generate Khmer names
     */
    protected function generateKhmerName()
    {
        $firstNames = ['សុខ', 'សុភា', 'វិសាល', 'ច័ន្ទ', 'សុវណ្ណ', 'រតនា', 'ពិសិដ្ឋ', 'មករា', 'សុធា', 'បញ្ញា'];
        $lastNames = ['លឹម', 'សុខ', 'ហេង', 'គង់', 'អ៊ុន', 'ចាន់', 'យ៉ាង', 'លី', 'សេង', 'តាន់'];
        
        return $this->faker->randomElement($firstNames) . ' ' . $this->faker->randomElement($lastNames);
    }
    
    /**
     * Get assessment date based on type
     */
    protected function getAssessmentDate($type)
    {
        switch ($type) {
            case 'baseline':
                return Carbon::now()->subMonths(3);
            case 'midline':
                return Carbon::now()->subMonths(1.5);
            case 'endline':
                return Carbon::now()->subDays(7);
            default:
                return Carbon::now();
        }
    }
    
    /**
     * Generate assessment note based on score
     */
    protected function generateAssessmentNote($score)
    {
        if ($score >= 80) {
            return 'Excellent progress. Student shows strong understanding.';
        } elseif ($score >= 60) {
            return 'Good progress. Continue with current learning path.';
        } elseif ($score >= 40) {
            return 'Moderate progress. Additional support recommended.';
        } else {
            return 'Needs intensive support. Consider remedial activities.';
        }
    }
    
    /**
     * Get performance level based on score
     */
    protected function getPerformanceLevel($score)
    {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'satisfactory';
        if ($score >= 40) return 'needs_improvement';
        return 'poor';
    }
    
    /**
     * Generate strengths observed
     */
    protected function generateStrengths()
    {
        $strengths = [
            'Good classroom management',
            'Effective use of TaRL methodology',
            'Strong student engagement',
            'Creative teaching approaches',
            'Good time management'
        ];
        
        return implode(', ', $this->faker->randomElements($strengths, 2));
    }
    
    /**
     * Generate areas for improvement
     */
    protected function generateImprovements()
    {
        $improvements = [
            'More group activities needed',
            'Better assessment documentation',
            'Increase student participation',
            'More differentiated instruction',
            'Improve material organization'
        ];
        
        return implode(', ', $this->faker->randomElements($improvements, 2));
    }
    
    /**
     * Generate support provided
     */
    protected function generateSupport()
    {
        $support = [
            'Demonstrated group learning techniques',
            'Provided assessment tools',
            'Shared best practices',
            'Conducted model lesson',
            'Provided learning materials'
        ];
        
        return implode(', ', $this->faker->randomElements($support, 2));
    }
    
    /**
     * Generate next steps
     */
    protected function generateNextSteps()
    {
        return 'Follow up visit scheduled. Focus on implementation of discussed strategies.';
    }
    
    /**
     * Generate activity outcome
     */
    protected function generateActivityOutcome()
    {
        $outcomes = [
            'Students showed improved understanding',
            'Good participation and engagement',
            'Concepts successfully introduced',
            'Skills practiced and reinforced',
            'Learning objectives achieved'
        ];
        
        return $this->faker->randomElement($outcomes);
    }
    
    /**
     * Generate progress note
     */
    protected function generateProgressNote()
    {
        $notes = [
            'Student showing steady improvement',
            'Good participation in class activities',
            'Needs more practice with basics',
            'Excellent progress this month',
            'Regular attendance, good effort'
        ];
        
        return $this->faker->randomElement($notes);
    }
}