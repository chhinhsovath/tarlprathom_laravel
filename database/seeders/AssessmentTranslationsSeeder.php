<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class AssessmentTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Assessment page specific
            ['key' => 'assessments', 'en' => 'Assessments', 'km' => 'ការវាយតម្លៃ', 'group' => 'assessment'],
            ['key' => 'select_students', 'en' => 'Select Students', 'km' => 'ជ្រើសរើសសិស្ស', 'group' => 'assessment'],
            ['key' => 'new_assessment', 'en' => 'New Assessment', 'km' => 'ការវាយតម្លៃថ្មី', 'group' => 'assessment'],
            ['key' => 'search_by_student_name', 'en' => 'Search by student name...', 'km' => 'ស្វែងរកតាមឈ្មោះសិស្ស...', 'group' => 'assessment'],
            ['key' => 'all_schools', 'en' => 'All Schools', 'km' => 'សាលាទាំងអស់', 'group' => 'assessment'],
            ['key' => 'all_subjects', 'en' => 'All Subjects', 'km' => 'មុខវិជ្ជាទាំងអស់', 'group' => 'assessment'],
            ['key' => 'all_cycles', 'en' => 'All Cycles', 'km' => 'វដ្តទាំងអស់', 'group' => 'assessment'],
            ['key' => 'all_grades', 'en' => 'All Grades', 'km' => 'ថ្នាក់ទាំងអស់', 'group' => 'assessment'],
            ['key' => 'khmer', 'en' => 'Khmer', 'km' => 'ភាសាខ្មែរ', 'group' => 'subject'],
            ['key' => 'math', 'en' => 'Math', 'km' => 'គណិតវិទ្យា', 'group' => 'subject'],
            ['key' => 'baseline', 'en' => 'Baseline', 'km' => 'មូលដ្ឋាន', 'group' => 'cycle'],
            ['key' => 'midline', 'en' => 'Midline', 'km' => 'ពាក់កណ្តាល', 'group' => 'cycle'],
            ['key' => 'endline', 'en' => 'Endline', 'km' => 'ចុងក្រោយ', 'group' => 'cycle'],
            ['key' => 'grade', 'en' => 'Grade', 'km' => 'ថ្នាក់', 'group' => 'general'],
            ['key' => 'search', 'en' => 'Search', 'km' => 'ស្វែងរក', 'group' => 'general'],
            ['key' => 'clear', 'en' => 'Clear', 'km' => 'សម្អាត', 'group' => 'general'],
            ['key' => 'export_to_excel', 'en' => 'Export to Excel', 'km' => 'នាំចេញទៅ Excel', 'group' => 'general'],
            ['key' => 'student_name', 'en' => 'Student Name', 'km' => 'ឈ្មោះសិស្ស', 'group' => 'assessment'],
            ['key' => 'school', 'en' => 'School', 'km' => 'សាលារៀន', 'group' => 'general'],
            ['key' => 'subject', 'en' => 'Subject', 'km' => 'មុខវិជ្ជា', 'group' => 'assessment'],
            ['key' => 'test_cycle', 'en' => 'Test Cycle', 'km' => 'វដ្តតេស្ត', 'group' => 'assessment'],
            ['key' => 'student_level', 'en' => 'Student Level', 'km' => 'កម្រិតសិស្ស', 'group' => 'assessment'],
            ['key' => 'date', 'en' => 'Date', 'km' => 'កាលបរិច្ឆេទ', 'group' => 'general'],
            ['key' => 'status', 'en' => 'Status', 'km' => 'ស្ថានភាព', 'group' => 'general'],
            ['key' => 'locked', 'en' => 'Locked', 'km' => 'ជាប់សោ', 'group' => 'general'],
            ['key' => 'active', 'en' => 'Active', 'km' => 'សកម្ម', 'group' => 'general'],
            ['key' => 'no_assessments_found', 'en' => 'No assessments found', 'km' => 'រកមិនឃើញការវាយតម្លៃ', 'group' => 'assessment'],

            // Assessment levels
            ['key' => 'story', 'en' => 'Story', 'km' => 'រឿង', 'group' => 'level'],
            ['key' => 'comp_1', 'en' => 'Comp. 1', 'km' => 'យល់អត្ថន័យ ១', 'group' => 'level'],
            ['key' => 'comp_2', 'en' => 'Comp. 2', 'km' => 'យល់អត្ថន័យ ២', 'group' => 'level'],
            ['key' => 'paragraph', 'en' => 'Paragraph', 'km' => 'កថាខណ្ឌ', 'group' => 'level'],
            ['key' => 'word', 'en' => 'Word', 'km' => 'ពាក្យ', 'group' => 'level'],
            ['key' => 'beginner', 'en' => 'Beginner', 'km' => 'ចាប់ផ្តើម', 'group' => 'level'],
            ['key' => 'letter', 'en' => 'Letter', 'km' => 'អក្សរ', 'group' => 'level'],
            ['key' => 'nothing', 'en' => 'Nothing', 'km' => 'គ្មាន', 'group' => 'level'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                    'group' => $translation['group'],
                ]
            );
        }

        // Clear translation cache
        Translation::clearCache();

        $this->command->info('Assessment translations seeded successfully!');
        $this->command->info('Total translations added: '.count($translations));
    }
}
