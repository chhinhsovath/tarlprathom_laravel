<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class DashboardTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Dashboard specific translations
            ['key' => 'loading_statistics', 'en' => 'Loading statistics...', 'km' => 'កំពុងផ្ទុកស្ថិតិ...', 'group' => 'dashboard'],
            ['key' => 'number_of_students', 'en' => 'Number of Students', 'km' => 'ចំនួនសិស្ស', 'group' => 'dashboard'],
            ['key' => 'students', 'en' => 'Students', 'km' => 'សិស្ស', 'group' => 'dashboard'],
            ['key' => 'percentage', 'en' => 'Percentage (%)', 'km' => 'ភាគរយ (%)', 'group' => 'dashboard'],
            ['key' => 'schools', 'en' => 'Schools', 'km' => 'សាលា', 'group' => 'dashboard'],
            ['key' => 'assessment_results', 'en' => 'Assessment Results', 'km' => 'លទ្ធផលវាយតម្លៃ', 'group' => 'dashboard'],
            ['key' => 'khmer', 'en' => 'Khmer', 'km' => 'ភាសាខ្មែរ', 'group' => 'dashboard'],
            ['key' => 'math', 'en' => 'Math', 'km' => 'គណិតវិទ្យា', 'group' => 'dashboard'],
            ['key' => 'overall_results', 'en' => 'Overall Results', 'km' => 'លទ្ធផលរួម', 'group' => 'dashboard'],
            ['key' => 'test_cycle', 'en' => 'Test Cycle', 'km' => 'វដ្តនៃការធ្វើតេស្ត', 'group' => 'dashboard'],
            ['key' => 'baseline', 'en' => 'Baseline', 'km' => 'មូលដ្ឋាន', 'group' => 'dashboard'],
            ['key' => 'midline', 'en' => 'Midline', 'km' => 'ពាក់កណ្តាល', 'group' => 'dashboard'],
            ['key' => 'endline', 'en' => 'Endline', 'km' => 'ចុងក្រោយ', 'group' => 'dashboard'],
            ['key' => 'results_by_school', 'en' => 'Results by School', 'km' => 'លទ្ធផលតាមសាលា', 'group' => 'dashboard'],
            ['key' => 'quick_actions', 'en' => 'Quick Actions', 'km' => 'សកម្មភាពរហ័ស', 'group' => 'dashboard'],
            ['key' => 'new_assessment', 'en' => 'New Assessment', 'km' => 'ការវាយតម្លៃថ្មី', 'group' => 'dashboard'],
            ['key' => 'log_visit', 'en' => 'Log Visit', 'km' => 'កត់ត្រាការទស្សនកិច្ច', 'group' => 'dashboard'],
            ['key' => 'view_reports', 'en' => 'View Reports', 'km' => 'មើលរបាយការណ៍', 'group' => 'dashboard'],
            ['key' => 'all_clusters', 'en' => 'All Clusters', 'km' => 'ក្រុមទាំងអស់', 'group' => 'geographic'],
            ['key' => 'all_schools', 'en' => 'All Schools', 'km' => 'សាលាទាំងអស់', 'group' => 'geographic'],
            ['key' => 'province', 'en' => 'Province', 'km' => 'ខេត្ត', 'group' => 'geographic'],
            ['key' => 'district', 'en' => 'District', 'km' => 'ស្រុក/ក្រុង', 'group' => 'geographic'],
            ['key' => 'cluster', 'en' => 'Cluster', 'km' => 'ក្រុម', 'group' => 'geographic'],
            ['key' => 'school', 'en' => 'School', 'km' => 'សាលា', 'group' => 'geographic'],
            ['key' => 'assessments', 'en' => 'Assessments', 'km' => 'ការវាយតម្លៃ', 'group' => 'dashboard'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                    'group' => $translation['group'],
                    'is_active' => true
                ]
            );
        }
    }
}