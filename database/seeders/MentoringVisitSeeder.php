<?php

namespace Database\Seeders;

use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MentoringVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get mentors, teachers, and schools - use schools table since that's what the foreign key references
        $mentors = User::where('role', 'mentor')->where('is_active', true)->get();
        $teachers = User::where('role', 'teacher')->where('is_active', true)->get();
        $schools = \App\Models\School::all(); // Use School model instead of PilotSchool for school_id foreign key

        if ($mentors->isEmpty() || $teachers->isEmpty() || $schools->isEmpty()) {
            $this->command->warn('Missing required data: mentors, teachers, or schools. Please seed users and schools first.');
            return;
        }

        // Real observation data templates
        $observations = [
            'Classroom Management' => [
                'Teacher demonstrated excellent classroom organization with clear seating arrangements.',
                'Students were engaged and followed instructions well throughout the lesson.',
                'Classroom materials were well-organized and easily accessible to students.',
                'Teacher effectively managed student behavior with positive reinforcement strategies.',
                'Learning environment was conducive to student participation and collaboration.',
            ],
            'Teaching Methods' => [
                'Teacher used interactive teaching methods to engage all students in the learning process.',
                'Visual aids and manipulatives were effectively used to support student understanding.',
                'Differentiated instruction was observed to meet diverse learning needs.',
                'Teacher provided clear explanations and checked for student understanding regularly.',
                'Hands-on activities were incorporated to make learning more meaningful.',
            ],
            'Student Engagement' => [
                'High level of student participation observed throughout the lesson.',
                'Students actively asked questions and engaged in meaningful discussions.',
                'Peer-to-peer learning was encouraged and facilitated effectively.',
                'Students demonstrated enthusiasm and interest in the subject matter.',
                'All students were given opportunities to participate regardless of their ability level.',
            ],
            'Assessment' => [
                'Teacher conducted ongoing formative assessment during the lesson.',
                'Students received immediate feedback on their work and progress.',
                'Various assessment strategies were used to gauge student understanding.',
                'Self-assessment opportunities were provided to students.',
                'Teacher effectively used assessment data to adjust instruction.',
            ],
        ];

        $actionPlans = [
            'Continue implementing group work strategies to enhance peer collaboration.',
            'Develop more visual resources to support students with different learning styles.',
            'Practice questioning techniques to encourage higher-order thinking skills.',
            'Create assessment rubrics for student self-evaluation activities.',
            'Integrate more technology tools to engage students in interactive learning.',
            'Establish regular feedback sessions with students to improve instructional practices.',
            'Develop differentiated materials for students at various proficiency levels.',
            'Implement more hands-on activities to support kinesthetic learners.',
            'Create anchor charts to support student independence during lessons.',
            'Design extension activities for advanced learners.',
        ];

        $subjects = ['Language', 'Numeracy'];
        $gradeGroups = ['Std. 1-2', 'Std. 3-6'];
        $languageLevels = ['Beginner', 'Letter Level', 'Word', 'Paragraph', 'Story'];
        $numeracyLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division'];

        $this->command->info('Creating realistic mentoring visits...');

        // Create 30 realistic mentoring visits over the past 6 months (limited by available schools)
        for ($i = 0; $i < 30; $i++) {
            $mentor = $mentors->random();
            // Use only school IDs that exist (1-13)
            $validSchoolIds = range(1, 13);
            $schoolId = $validSchoolIds[array_rand($validSchoolIds)];
            $school = $schools->where('id', $schoolId)->first();
            if (!$school) {
                $school = $schools->first(); // Fallback to first school
            }
            $teacher = $teachers->random();
            
            $visitDate = Carbon::now()->subMonths(rand(0, 6))->subDays(rand(0, 30));
            $subject = $subjects[array_rand($subjects)];
            $gradeGroup = $gradeGroups[array_rand($gradeGroups)];
            
            // Select appropriate levels based on subject
            $levelsObserved = $subject === 'Language' 
                ? collect($languageLevels)->random(rand(1, 3))->toArray()
                : collect($numeracyLevels)->random(rand(1, 3))->toArray();

            // Generate questionnaire data
            $questionnaireData = [
                'total_students_enrolled' => rand(15, 35),
                'students_present' => rand(12, 30),
                'students_improved_from_last_week' => rand(5, 15),
                'classes_conducted_before_visit' => rand(8, 20),
                'class_started_on_time' => rand(0, 1) === 1,
                'late_start_reason' => rand(0, 1) === 1 ? 'Teacher was preparing materials' : null,
                'materials_present' => collect(['Books', 'Worksheets', 'Visual aids', 'Manipulatives'])->random(rand(2, 4))->toArray(),
                'children_grouped_appropriately' => rand(0, 1) === 1,
                'students_fully_involved' => rand(0, 1) === 1,
                'has_session_plan' => rand(0, 1) === 1,
                'followed_session_plan' => rand(0, 1) === 1,
                'session_plan_appropriate' => rand(0, 1) === 1,
                'number_of_activities' => rand(2, 4),
            ];

            // Add activity-specific data
            for ($actNum = 1; $actNum <= $questionnaireData['number_of_activities']; $actNum++) {
                $questionnaireData["activity{$actNum}_name_{$subject}"] = "Activity {$actNum} - " . ($subject === 'Language' ? 'Reading comprehension' : 'Number operations');
                $questionnaireData["activity{$actNum}_duration"] = rand(10, 20);
                $questionnaireData["activity{$actNum}_clear_instructions"] = rand(0, 1) === 1;
                $questionnaireData["activity{$actNum}_demonstrated"] = rand(0, 1) === 1;
                $questionnaireData["activity{$actNum}_followed_process"] = rand(0, 1) === 1;
                $questionnaireData["activity{$actNum}_students_practice"] = rand(0, 1) === 1;
                $questionnaireData["activity{$actNum}_small_groups"] = rand(0, 1) === 1;
                $questionnaireData["activity{$actNum}_individual"] = rand(0, 1) === 1;
            }

            MentoringVisit::create([
                'mentor_id' => $mentor->id,
                'school_id' => $school->id,
                'teacher_id' => $teacher->id,
                'visit_date' => $visitDate,
                'observation' => collect($observations[array_rand($observations)])->random(),
                'action_plan' => $actionPlans[array_rand($actionPlans)],
                'score' => rand(60, 100),
                'follow_up_required' => rand(0, 1) === 1,
                'region' => 'Central',
                'province' => $school->province ?? 'Phnom Penh',
                'program_type' => 'TaRL Program',
                'class_in_session' => rand(0, 1) === 1,
                'class_not_in_session_reason' => rand(0, 1) === 1 ? 'Teacher is Absent' : null,
                'full_session_observed' => rand(0, 1) === 1,
                'grade_group' => $gradeGroup,
                'grades_observed' => $gradeGroup === 'Std. 1-2' ? ['1', '2'] : ['3', '4', '5', '6'],
                'subject_observed' => $subject,
                'language_levels_observed' => $subject === 'Language' ? $levelsObserved : null,
                'numeracy_levels_observed' => $subject === 'Numeracy' ? $levelsObserved : null,
                'questionnaire_data' => $questionnaireData,
                'is_locked' => rand(0, 10) === 1, // 10% chance of being locked
                'locked_by' => rand(0, 10) === 1 ? $mentor->id : null,
                'locked_at' => rand(0, 10) === 1 ? $visitDate->addDays(rand(1, 5)) : null,
                'created_at' => $visitDate,
                'updated_at' => $visitDate->addHours(rand(1, 24)),
            ]);

            if ($i % 20 === 0) {
                $this->command->info("Created {$i} mentoring visits...");
            }
        }

        $this->command->info('Created 30 realistic mentoring visits with comprehensive data.');

        // Create some recent visits for testing
        $this->command->info('Creating recent visits for testing...');
        
        for ($i = 0; $i < 10; $i++) {
            $mentor = $mentors->random();
            $validSchoolIds = range(1, 13);
            $schoolId = $validSchoolIds[array_rand($validSchoolIds)];
            $school = $schools->where('id', $schoolId)->first() ?? $schools->first();
            $teacher = $teachers->random();
            
            MentoringVisit::create([
                'mentor_id' => $mentor->id,
                'school_id' => $school->id,
                'teacher_id' => $teacher->id,
                'visit_date' => Carbon::now()->subDays(rand(1, 7)),
                'observation' => 'Recent visit observation: ' . collect($observations['Teaching Methods'])->random(),
                'action_plan' => 'Recent action plan: ' . $actionPlans[array_rand($actionPlans)],
                'score' => rand(70, 95),
                'follow_up_required' => true,
                'region' => 'Central',
                'province' => 'Phnom Penh',
                'program_type' => 'TaRL Program',
                'class_in_session' => true,
                'full_session_observed' => true,
                'grade_group' => $gradeGroups[array_rand($gradeGroups)],
                'grades_observed' => ['1', '2', '3'],
                'subject_observed' => $subjects[array_rand($subjects)],
                'language_levels_observed' => ['Letter Level', 'Word'],
                'questionnaire_data' => [
                    'total_students_enrolled' => 25,
                    'students_present' => 23,
                    'class_started_on_time' => true,
                    'has_session_plan' => true,
                    'number_of_activities' => 3,
                ],
            ]);
        }

        $this->command->info('Successfully seeded mentoring visits with realistic data!');
    }
}