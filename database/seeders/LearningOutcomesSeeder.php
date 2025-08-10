<?php

namespace Database\Seeders;

use App\Models\LearningOutcome;
use Illuminate\Database\Seeder;

class LearningOutcomesSeeder extends Seeder
{
    public function run(): void
    {
        $learningOutcomes = [
            // Khmer Language Learning Outcomes - Grade 1
            [
                'code' => 'KH-G1-01',
                'name' => 'Letter Recognition - ក ខ គ',
                'description' => 'Recognize and identify basic Khmer consonants ក ខ គ',
                'subject' => 'khmer',
                'grade_level' => '1',
                'domain' => 'knowledge',
                'cognitive_level' => 'remember',
                'sequence_order' => 1,
                'assessment_criteria' => ['Can identify letter ក', 'Can identify letter ខ', 'Can identify letter គ'],
                'performance_indicators' => ['Points to correct letter when shown', 'Names letter correctly'],
                'minimum_mastery_score' => 70,
                'target_weeks_to_achieve' => 2,
                'teaching_strategies' => ['Flash cards', 'Letter tracing', 'Memory games'],
                'learning_resources' => ['Khmer alphabet chart', 'Letter cards', 'Practice worksheets'],
                'is_core_outcome' => true,
                'difficulty_level' => 'beginner',
            ],
            [
                'code' => 'KH-G1-02',
                'name' => 'Simple Word Reading',
                'description' => 'Read simple one-syllable Khmer words',
                'subject' => 'khmer',
                'grade_level' => '1',
                'domain' => 'skills',
                'cognitive_level' => 'apply',
                'sequence_order' => 2,
                'prerequisite_outcomes' => ['KH-G1-01'],
                'assessment_criteria' => ['Can read 5 simple words correctly', 'Pronunciation is clear'],
                'performance_indicators' => ['Reads words without hesitation', 'Understands word meaning'],
                'minimum_mastery_score' => 60,
                'target_weeks_to_achieve' => 4,
                'teaching_strategies' => ['Phonics method', 'Word games', 'Repetitive reading'],
                'learning_resources' => ['Simple story books', 'Word lists', 'Audio recordings'],
                'is_core_outcome' => true,
                'difficulty_level' => 'beginner',
            ],

            // Math Learning Outcomes - Grade 1
            [
                'code' => 'MA-G1-01',
                'name' => 'Number Recognition 1-10',
                'description' => 'Recognize and write numbers from 1 to 10',
                'subject' => 'math',
                'grade_level' => '1',
                'domain' => 'knowledge',
                'cognitive_level' => 'remember',
                'sequence_order' => 1,
                'assessment_criteria' => ['Can identify all numbers 1-10', 'Can write numbers correctly'],
                'performance_indicators' => ['Points to correct number when shown', 'Writes numbers legibly'],
                'minimum_mastery_score' => 80,
                'target_weeks_to_achieve' => 3,
                'teaching_strategies' => ['Number songs', 'Counting games', 'Number tracing'],
                'learning_resources' => ['Number cards', 'Counting objects', 'Number line'],
                'is_core_outcome' => true,
                'difficulty_level' => 'beginner',
            ],
            [
                'code' => 'MA-G1-02',
                'name' => 'Simple Addition (1-5)',
                'description' => 'Perform simple addition with numbers 1-5',
                'subject' => 'math',
                'grade_level' => '1',
                'domain' => 'skills',
                'cognitive_level' => 'apply',
                'sequence_order' => 2,
                'prerequisite_outcomes' => ['MA-G1-01'],
                'assessment_criteria' => ['Can solve 8 out of 10 addition problems', 'Uses correct method'],
                'performance_indicators' => ['Shows working clearly', 'Gets correct answers'],
                'minimum_mastery_score' => 70,
                'target_weeks_to_achieve' => 4,
                'teaching_strategies' => ['Manipulatives use', 'Visual aids', 'Step-by-step method'],
                'learning_resources' => ['Counting beads', 'Addition worksheets', 'Visual number line'],
                'is_core_outcome' => true,
                'difficulty_level' => 'beginner',
            ],

            // Khmer Language Learning Outcomes - Grade 2
            [
                'code' => 'KH-G2-01',
                'name' => 'Sentence Reading',
                'description' => 'Read simple sentences with fluency',
                'subject' => 'khmer',
                'grade_level' => '2',
                'domain' => 'skills',
                'cognitive_level' => 'understand',
                'sequence_order' => 1,
                'prerequisite_outcomes' => ['KH-G1-02'],
                'assessment_criteria' => ['Can read sentences smoothly', 'Understands sentence meaning'],
                'performance_indicators' => ['Reads with proper intonation', 'Answers comprehension questions'],
                'minimum_mastery_score' => 65,
                'target_weeks_to_achieve' => 6,
                'teaching_strategies' => ['Guided reading', 'Repeated reading', 'Comprehension questions'],
                'learning_resources' => ['Graded Letters', 'Story books', 'Reading passages'],
                'is_core_outcome' => true,
                'difficulty_level' => 'intermediate',
            ],

            // Math Learning Outcomes - Grade 2
            [
                'code' => 'MA-G2-01',
                'name' => 'Two-Digit Numbers',
                'description' => 'Understand and work with two-digit numbers',
                'subject' => 'math',
                'grade_level' => '2',
                'domain' => 'knowledge',
                'cognitive_level' => 'understand',
                'sequence_order' => 1,
                'prerequisite_outcomes' => ['MA-G1-02'],
                'assessment_criteria' => ['Can identify place value', 'Can compare two-digit numbers'],
                'performance_indicators' => ['Explains tens and ones', 'Orders numbers correctly'],
                'minimum_mastery_score' => 75,
                'target_weeks_to_achieve' => 5,
                'teaching_strategies' => ['Place value blocks', 'Number comparison', 'Ordering activities'],
                'learning_resources' => ['Base-10 blocks', 'Number charts', 'Place value worksheets'],
                'is_core_outcome' => true,
                'difficulty_level' => 'intermediate',
            ],

            // Grade 3 Advanced Outcomes
            [
                'code' => 'KH-G3-01',
                'name' => 'Story Comprehension',
                'description' => 'Read and understand complete stories',
                'subject' => 'khmer',
                'grade_level' => '3',
                'domain' => 'skills',
                'cognitive_level' => 'analyze',
                'sequence_order' => 1,
                'prerequisite_outcomes' => ['KH-G2-01'],
                'assessment_criteria' => ['Can summarize story', 'Can identify main characters', 'Can predict outcomes'],
                'performance_indicators' => ['Retells story accurately', 'Answers higher-order questions'],
                'minimum_mastery_score' => 60,
                'target_weeks_to_achieve' => 8,
                'teaching_strategies' => ['Story mapping', 'Character analysis', 'Prediction activities'],
                'learning_resources' => ['Chapter books', 'Story worksheets', 'Comprehension questions'],
                'is_core_outcome' => true,
                'difficulty_level' => 'advanced',
            ],
            [
                'code' => 'MA-G3-01',
                'name' => 'Multiplication Tables',
                'description' => 'Master multiplication tables 1-5',
                'subject' => 'math',
                'grade_level' => '3',
                'domain' => 'skills',
                'cognitive_level' => 'apply',
                'sequence_order' => 1,
                'prerequisite_outcomes' => ['MA-G2-01'],
                'assessment_criteria' => ['Can recite tables fluently', 'Can solve word problems'],
                'performance_indicators' => ['Answers quickly and accurately', 'Applies to real situations'],
                'minimum_mastery_score' => 80,
                'target_weeks_to_achieve' => 10,
                'teaching_strategies' => ['Repeated practice', 'Pattern recognition', 'Real-world applications'],
                'learning_resources' => ['Multiplication charts', 'Fact family cards', 'Word problem worksheets'],
                'is_core_outcome' => true,
                'difficulty_level' => 'advanced',
            ],
        ];

        foreach ($learningOutcomes as $outcome) {
            LearningOutcome::create($outcome);
        }
    }
}
