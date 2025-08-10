<?php

namespace Database\Seeders;

use App\Models\InterventionProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class InterventionProgramsSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first admin user to assign as creator
        $creator = User::where('role', 'admin')->first();

        $programs = [
            [
                'program_name' => 'Reading Support Program',
                'description' => 'Intensive reading support for students struggling with basic literacy skills',
                'type' => 'academic',
                'intensity' => 'tier2_targeted',
                'target_criteria' => [
                    [
                        'type' => 'assessment_score',
                        'subject' => 'khmer',
                        'cycle' => 'baseline',
                        'operator' => '<',
                        'value' => 40,
                    ],
                ],
                'objectives' => [
                    'Improve letter recognition by 80%',
                    'Achieve basic word reading fluency',
                    'Develop phonemic awareness',
                ],
                'duration_weeks' => 12,
                'sessions_per_week' => 3,
                'minutes_per_session' => 45,
                'delivery_method' => 'Small group instruction (3-5 students)',
                'materials_needed' => [
                    'Phonics workbooks',
                    'Letter cards',
                    'Reading passages',
                    'Audio recordings',
                ],
                'success_metrics' => [
                    'Improvement in assessment scores by at least 30%',
                    'Ability to read 20 sight words',
                    'Complete 80% of program sessions',
                ],
                'success_threshold' => 70.0,
                'implementation_steps' => [
                    'Initial assessment and goal setting',
                    'Intensive phonics instruction',
                    'Daily reading practice',
                    'Progress monitoring weekly',
                    'Final assessment and transition',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'program_name' => 'Math Foundations Program',
                'description' => 'Basic numeracy skills development for students with math difficulties',
                'type' => 'academic',
                'intensity' => 'tier2_targeted',
                'target_criteria' => [
                    [
                        'type' => 'assessment_score',
                        'subject' => 'math',
                        'cycle' => 'baseline',
                        'operator' => '<',
                        'value' => 35,
                    ],
                ],
                'objectives' => [
                    'Master number recognition 1-20',
                    'Understand basic addition and subtraction',
                    'Develop number sense',
                ],
                'duration_weeks' => 10,
                'sessions_per_week' => 4,
                'minutes_per_session' => 30,
                'delivery_method' => 'Individual tutoring with peer support',
                'materials_needed' => [
                    'Counting manipulatives',
                    'Number charts',
                    'Math worksheets',
                    'Visual aids',
                ],
                'success_metrics' => [
                    'Score 70% or higher on number recognition test',
                    'Solve 8 out of 10 basic addition problems',
                    'Demonstrate improved confidence in math',
                ],
                'success_threshold' => 75.0,
                'implementation_steps' => [
                    'Diagnostic assessment',
                    'Concrete manipulative stage',
                    'Semi-concrete representation',
                    'Abstract number work',
                    'Mastery verification',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'program_name' => 'Attendance Improvement Program',
                'description' => 'Comprehensive intervention for students with chronic absenteeism',
                'type' => 'attendance',
                'intensity' => 'tier3_intensive',
                'target_criteria' => [
                    [
                        'type' => 'attendance_rate',
                        'operator' => '<',
                        'value' => 70,
                    ],
                ],
                'objectives' => [
                    'Increase attendance rate to 85% or higher',
                    'Identify and address barriers to attendance',
                    'Strengthen family-school connection',
                ],
                'duration_weeks' => 16,
                'sessions_per_week' => 2,
                'minutes_per_session' => 60,
                'delivery_method' => 'Family engagement and individual counseling',
                'materials_needed' => [
                    'Attendance tracking sheets',
                    'Family communication materials',
                    'Transportation vouchers',
                    'Health referral forms',
                ],
                'success_metrics' => [
                    'Attendance rate improves by at least 20%',
                    'Reduced tardiness incidents',
                    'Increased family engagement',
                ],
                'success_threshold' => 85.0,
                'implementation_steps' => [
                    'Home visit and needs assessment',
                    'Develop attendance action plan',
                    'Weekly check-ins and support',
                    'Address identified barriers',
                    'Celebrate attendance milestones',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'program_name' => 'Nutrition Support Program',
                'description' => 'Health and nutrition intervention for malnourished students',
                'type' => 'nutrition',
                'intensity' => 'tier2_targeted',
                'target_criteria' => [
                    [
                        'type' => 'nutrition_status',
                        'operator' => '=',
                        'value' => 'malnourished',
                    ],
                ],
                'objectives' => [
                    'Improve nutritional status to normal range',
                    'Increase weight and height appropriately',
                    'Enhance learning capacity through better nutrition',
                ],
                'duration_weeks' => 24,
                'sessions_per_week' => 5,
                'minutes_per_session' => 20,
                'delivery_method' => 'Daily meal supplements and health monitoring',
                'materials_needed' => [
                    'Nutritional supplements',
                    'Growth monitoring charts',
                    'Health education materials',
                    'Family nutrition guides',
                ],
                'success_metrics' => [
                    'Weight gain of 2-3 kg over 6 months',
                    'Improved energy levels in classroom',
                    'Better academic performance',
                ],
                'success_threshold' => 80.0,
                'implementation_steps' => [
                    'Health assessment and baseline measurements',
                    'Daily nutritional supplementation',
                    'Monthly growth monitoring',
                    'Family nutrition education',
                    'Transition to regular meal program',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'program_name' => 'Behavioral Support Program',
                'description' => 'Positive behavior intervention for students with classroom difficulties',
                'type' => 'behavioral',
                'intensity' => 'tier2_targeted',
                'target_criteria' => [
                    [
                        'type' => 'behavior_incidents',
                        'operator' => '>',
                        'value' => 3,
                    ],
                ],
                'objectives' => [
                    'Reduce disruptive behaviors by 75%',
                    'Increase on-task behavior to 85%',
                    'Improve social skills and peer relationships',
                ],
                'duration_weeks' => 8,
                'sessions_per_week' => 3,
                'minutes_per_session' => 30,
                'delivery_method' => 'Individual and group counseling with behavior plan',
                'materials_needed' => [
                    'Behavior tracking charts',
                    'Social skills curriculum',
                    'Reward system materials',
                    'Communication tools',
                ],
                'success_metrics' => [
                    'Behavior incidents reduced by 75%',
                    'Increased positive teacher reports',
                    'Improved peer interactions',
                ],
                'success_threshold' => 75.0,
                'implementation_steps' => [
                    'Functional behavior assessment',
                    'Develop behavior intervention plan',
                    'Implement positive behavior supports',
                    'Regular progress monitoring',
                    'Plan for behavior maintenance',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'program_name' => 'Social-Emotional Learning Program',
                'description' => 'Comprehensive program to develop emotional intelligence and social skills',
                'type' => 'social_emotional',
                'intensity' => 'tier1_universal',
                'target_criteria' => [
                    [
                        'type' => 'grade_level',
                        'operator' => '=',
                        'value' => '1,2,3',
                    ],
                ],
                'objectives' => [
                    'Develop emotional self-awareness',
                    'Improve emotion regulation skills',
                    'Enhance social interaction abilities',
                ],
                'duration_weeks' => 20,
                'sessions_per_week' => 2,
                'minutes_per_session' => 25,
                'delivery_method' => 'Whole class instruction with small group activities',
                'materials_needed' => [
                    'SEL curriculum materials',
                    'Emotion regulation tools',
                    'Role-play scenarios',
                    'Assessment rubrics',
                ],
                'success_metrics' => [
                    'Improved emotion recognition scores',
                    'Reduced peer conflicts',
                    'Increased prosocial behaviors',
                ],
                'success_threshold' => 70.0,
                'implementation_steps' => [
                    'SEL skills assessment',
                    'Explicit instruction in core competencies',
                    'Practice through guided activities',
                    'Real-world application opportunities',
                    'Progress monitoring and feedback',
                ],
                'created_by' => $creator?->id,
                'is_active' => true,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
        ];

        foreach ($programs as $program) {
            InterventionProgram::create($program);
        }
    }
}
