<?php

namespace Database\Seeders;

use App\Models\TeachingActivity;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeachingActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first admin user to assign as creator
        $creator = User::where('role', 'admin')->first();

        $activities = [
            // Khmer Language Activities
            [
                'activity_code' => 'KH-ACT-001',
                'activity_name' => 'Letter Sound Matching Game',
                'description' => 'Interactive game where students match Khmer letters with their corresponding sounds',
                'subject' => 'khmer',
                'grade_level' => '1,2',
                'tarl_level' => 'beginner',
                'activity_type' => 'small_group',
                'duration_minutes' => 20,
                'learning_objectives' => [
                    'Recognize Khmer consonants',
                    'Associate letters with sounds',
                    'Develop phonemic awareness'
                ],
                'materials_required' => [
                    'Letter cards',
                    'Sound recording device',
                    'Picture cards',
                    'Game board'
                ],
                'preparation_steps' => [
                    'Prepare letter and picture cards',
                    'Set up game board',
                    'Test sound equipment',
                    'Organize student groups'
                ],
                'implementation_steps' => [
                    'Introduce the game rules',
                    'Demonstrate with one example',
                    'Students take turns matching',
                    'Provide immediate feedback',
                    'Celebrate correct matches'
                ],
                'assessment_strategies' => [
                    'Observe student accuracy',
                    'Note participation level',
                    'Record time to completion',
                    'Ask reflection questions'
                ],
                'differentiation_strategies' => [
                    'Vary number of letters for different abilities',
                    'Provide peer support for struggling students',
                    'Add complexity for advanced learners'
                ],
                'extension_activities' => [
                    'Create own letter-sound combinations',
                    'Teach the game to younger students',
                    'Draw pictures for letters'
                ],
                'keywords' => ['letters', 'sounds', 'phonics', 'matching', 'beginner'],
                'difficulty_level' => 'easy',
                'requires_technology' => false,
                'indoor_activity' => true,
                'space_requirements' => ['Classroom floor space', 'Tables for cards'],
                'minimum_students' => 3,
                'maximum_students' => 8,
                'skills_developed' => [
                    'Letter recognition',
                    'Sound discrimination',
                    'Memory skills',
                    'Turn-taking'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],
            [
                'activity_code' => 'KH-ACT-002',
                'activity_name' => 'Story Circle Reading',
                'description' => 'Collaborative story reading where students take turns reading sentences',
                'subject' => 'khmer',
                'grade_level' => '2,3',
                'tarl_level' => 'word',
                'activity_type' => 'whole_class',
                'duration_minutes' => 30,
                'learning_objectives' => [
                    'Improve reading fluency',
                    'Build confidence in oral reading',
                    'Develop listening skills'
                ],
                'materials_required' => [
                    'Age-appropriate story books',
                    'Reading pointers',
                    'Comfortable seating'
                ],
                'preparation_steps' => [
                    'Select appropriate story',
                    'Preview difficult words',
                    'Arrange seating in circle',
                    'Prepare discussion questions'
                ],
                'implementation_steps' => [
                    'Introduce the story and characters',
                    'Each student reads one sentence',
                    'Help with difficult words',
                    'Discuss story as you go',
                    'Reflect on story meaning'
                ],
                'assessment_strategies' => [
                    'Note reading fluency improvements',
                    'Assess comprehension through questions',
                    'Observe engagement levels',
                    'Record new vocabulary learned'
                ],
                'differentiation_strategies' => [
                    'Shorter sentences for struggling readers',
                    'Allow re-reading for confidence',
                    'Pair strong readers with weaker ones'
                ],
                'extension_activities' => [
                    'Act out parts of the story',
                    'Draw favorite scenes',
                    'Create alternative endings'
                ],
                'keywords' => ['reading', 'fluency', 'collaboration', 'stories'],
                'difficulty_level' => 'medium',
                'requires_technology' => false,
                'indoor_activity' => true,
                'space_requirements' => ['Circle seating arrangement'],
                'minimum_students' => 8,
                'maximum_students' => 25,
                'skills_developed' => [
                    'Reading fluency',
                    'Listening skills',
                    'Story comprehension',
                    'Confidence building'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],

            // Math Activities
            [
                'activity_code' => 'MA-ACT-001',
                'activity_name' => 'Number Hunt Adventure',
                'description' => 'Active game where students find and collect numbers in sequence',
                'subject' => 'math',
                'grade_level' => '1',
                'tarl_level' => 'beginner',
                'activity_type' => 'small_group',
                'duration_minutes' => 25,
                'learning_objectives' => [
                    'Recognize numbers 1-20',
                    'Understand number sequence',
                    'Develop counting skills'
                ],
                'materials_required' => [
                    'Number cards (1-20)',
                    'Collection baskets',
                    'Timer',
                    'Stickers for rewards'
                ],
                'preparation_steps' => [
                    'Hide number cards around classroom',
                    'Prepare collection baskets',
                    'Set up start and finish areas',
                    'Explain rules clearly'
                ],
                'implementation_steps' => [
                    'Students search for numbers in order',
                    'Collect numbers 1, then 2, then 3, etc.',
                    'Return to check with teacher',
                    'Continue until complete',
                    'Celebrate completion'
                ],
                'assessment_strategies' => [
                    'Check number recognition accuracy',
                    'Time how long it takes',
                    'Note which numbers are difficult',
                    'Observe cooperation skills'
                ],
                'differentiation_strategies' => [
                    'Use different number ranges (1-10, 1-15, 1-20)',
                    'Allow peer helpers',
                    'Provide number line reference'
                ],
                'extension_activities' => [
                    'Hide numbers in skip counting patterns',
                    'Add simple math problems',
                    'Create treasure maps with numbers'
                ],
                'keywords' => ['numbers', 'counting', 'sequence', 'active learning'],
                'difficulty_level' => 'easy',
                'requires_technology' => false,
                'indoor_activity' => false,
                'space_requirements' => ['Large classroom or outdoor space'],
                'minimum_students' => 4,
                'maximum_students' => 12,
                'skills_developed' => [
                    'Number recognition',
                    'Counting skills',
                    'Physical activity',
                    'Problem solving'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],
            [
                'activity_code' => 'MA-ACT-002',
                'activity_name' => 'Addition with Manipulatives',
                'description' => 'Hands-on addition practice using concrete objects',
                'subject' => 'math',
                'grade_level' => '1,2',
                'tarl_level' => '1-digit',
                'activity_type' => 'pair',
                'duration_minutes' => 35,
                'learning_objectives' => [
                    'Understand addition concept',
                    'Use concrete materials for calculation',
                    'Connect abstract symbols with concrete objects'
                ],
                'materials_required' => [
                    'Counting beads or blocks',
                    'Addition worksheets',
                    'Number cards',
                    'Recording sheets'
                ],
                'preparation_steps' => [
                    'Prepare manipulative sets for each pair',
                    'Create addition problems appropriate for level',
                    'Set up workspace with materials',
                    'Prepare recording sheets'
                ],
                'implementation_steps' => [
                    'Demonstrate addition with manipulatives',
                    'Students work in pairs on problems',
                    'Use objects to show each addition',
                    'Record the number sentence',
                    'Check answers together'
                ],
                'assessment_strategies' => [
                    'Observe correct use of manipulatives',
                    'Check accuracy of recorded answers',
                    'Note problem-solving strategies',
                    'Listen to student explanations'
                ],
                'differentiation_strategies' => [
                    'Vary problem difficulty (sums to 5, 10, 15)',
                    'Provide different types of manipulatives',
                    'Allow verbal or written recording'
                ],
                'extension_activities' => [
                    'Create word problems',
                    'Explore subtraction with same materials',
                    'Make up problems for other students'
                ],
                'keywords' => ['addition', 'manipulatives', 'concrete', 'pairs'],
                'difficulty_level' => 'medium',
                'requires_technology' => false,
                'indoor_activity' => true,
                'space_requirements' => ['Tables for pair work'],
                'minimum_students' => 2,
                'maximum_students' => 20,
                'skills_developed' => [
                    'Addition skills',
                    'Number sense',
                    'Mathematical reasoning',
                    'Collaborative skills'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],

            // Advanced Activities
            [
                'activity_code' => 'KH-ACT-003',
                'activity_name' => 'Character Analysis Discussion',
                'description' => 'Deep discussion about story characters and their motivations',
                'subject' => 'khmer',
                'grade_level' => '3',
                'tarl_level' => 'story',
                'activity_type' => 'whole_class',
                'duration_minutes' => 40,
                'learning_objectives' => [
                    'Analyze character traits and motivations',
                    'Express opinions about characters',
                    'Support opinions with evidence from text'
                ],
                'materials_required' => [
                    'Complete story book',
                    'Character analysis worksheet',
                    'Discussion question cards',
                    'Chart paper for recording ideas'
                ],
                'preparation_steps' => [
                    'Read story beforehand',
                    'Prepare discussion questions',
                    'Set up comfortable discussion area',
                    'Prepare character analysis templates'
                ],
                'implementation_steps' => [
                    'Review story briefly',
                    'Introduce main characters',
                    'Discuss character traits and evidence',
                    'Students share different perspectives',
                    'Record key insights on chart'
                ],
                'assessment_strategies' => [
                    'Listen for evidence-based reasoning',
                    'Note depth of character understanding',
                    'Assess participation in discussion',
                    'Review written character analyses'
                ],
                'differentiation_strategies' => [
                    'Provide sentence starters for responses',
                    'Allow drawing instead of writing',
                    'Pair students for discussion support'
                ],
                'extension_activities' => [
                    'Write alternative character actions',
                    'Create character dialogue',
                    'Compare characters across stories'
                ],
                'keywords' => ['character analysis', 'discussion', 'critical thinking'],
                'difficulty_level' => 'hard',
                'requires_technology' => false,
                'indoor_activity' => true,
                'space_requirements' => ['Discussion circle area'],
                'minimum_students' => 10,
                'maximum_students' => 25,
                'skills_developed' => [
                    'Critical thinking',
                    'Oral communication',
                    'Text analysis',
                    'Evidence-based reasoning'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],
            [
                'activity_code' => 'MA-ACT-003',
                'activity_name' => 'Word Problems Workshop',
                'description' => 'Collaborative problem-solving with real-world math scenarios',
                'subject' => 'math',
                'grade_level' => '2,3',
                'tarl_level' => '2-digit',
                'activity_type' => 'small_group',
                'duration_minutes' => 45,
                'learning_objectives' => [
                    'Solve multi-step word problems',
                    'Identify relevant information',
                    'Choose appropriate problem-solving strategies'
                ],
                'materials_required' => [
                    'Word problem cards',
                    'Manipulatives for calculation',
                    'Strategy posters',
                    'Recording sheets'
                ],
                'preparation_steps' => [
                    'Select problems appropriate for student levels',
                    'Organize materials by group',
                    'Display problem-solving strategies',
                    'Prepare solution recording sheets'
                ],
                'implementation_steps' => [
                    'Read problem together carefully',
                    'Identify what we know and need to find',
                    'Choose problem-solving strategy',
                    'Work through solution step by step',
                    'Check answer for reasonableness'
                ],
                'assessment_strategies' => [
                    'Observe problem-solving process',
                    'Check accuracy of final answers',
                    'Note strategy choices',
                    'Listen to group discussions'
                ],
                'differentiation_strategies' => [
                    'Provide problems with different complexity',
                    'Allow use of manipulatives or drawing',
                    'Offer hint cards for struggling groups'
                ],
                'extension_activities' => [
                    'Create their own word problems',
                    'Solve problems multiple ways',
                    'Present solutions to other groups'
                ],
                'keywords' => ['word problems', 'problem solving', 'real-world'],
                'difficulty_level' => 'hard',
                'requires_technology' => false,
                'indoor_activity' => true,
                'space_requirements' => ['Tables for group work'],
                'minimum_students' => 9,
                'maximum_students' => 20,
                'skills_developed' => [
                    'Problem solving',
                    'Mathematical reasoning',
                    'Reading comprehension',
                    'Collaboration'
                ],
                'created_by' => $creator?->id,
                'is_approved' => true,
                'approved_by' => $creator?->id,
                'approved_at' => now(),
                'is_active' => true,
            ],
        ];

        foreach ($activities as $activity) {
            TeachingActivity::create($activity);
        }
    }
}