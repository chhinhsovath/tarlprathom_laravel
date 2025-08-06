<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class AdditionalTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Dashboard specific
            ['key' => 'filter_data', 'en' => 'Filter Data', 'km' => 'ត្រងទិន្នន័យ', 'group' => 'dashboard'],
            ['key' => 'cluster', 'en' => 'Cluster', 'km' => 'ក្រុម', 'group' => 'dashboard'],
            ['key' => 'all_clusters', 'en' => 'All Clusters', 'km' => 'ក្រុមទាំងអស់', 'group' => 'dashboard'],
            ['key' => 'school', 'en' => 'School', 'km' => 'សាលារៀន', 'group' => 'dashboard'],
            ['key' => 'performance_overview', 'en' => 'Performance Overview', 'km' => 'ទិដ្ឋភាពទូទៅនៃលទ្ធផល', 'group' => 'dashboard'],
            ['key' => 'assessment_progress', 'en' => 'Assessment Progress', 'km' => 'វឌ្ឍនភាពនៃការវាយតម្លៃ', 'group' => 'dashboard'],
            ['key' => 'recent_activities', 'en' => 'Recent Activities', 'km' => 'សកម្មភាពថ្មីៗ', 'group' => 'dashboard'],
            ['key' => 'quick_stats', 'en' => 'Quick Stats', 'km' => 'ស្ថិតិរហ័ស', 'group' => 'dashboard'],
            
            // Filter related
            ['key' => 'apply_filters', 'en' => 'Apply Filters', 'km' => 'អនុវត្តការត្រង', 'group' => 'filters'],
            ['key' => 'clear_filters', 'en' => 'Clear Filters', 'km' => 'សម្អាតការត្រង', 'group' => 'filters'],
            ['key' => 'filter_by', 'en' => 'Filter by', 'km' => 'ត្រងតាម', 'group' => 'filters'],
            
            // Time periods
            ['key' => 'today', 'en' => 'Today', 'km' => 'ថ្ងៃនេះ', 'group' => 'time'],
            ['key' => 'yesterday', 'en' => 'Yesterday', 'km' => 'ម្សិលមិញ', 'group' => 'time'],
            ['key' => 'this_week', 'en' => 'This Week', 'km' => 'សប្តាហ៍នេះ', 'group' => 'time'],
            ['key' => 'last_week', 'en' => 'Last Week', 'km' => 'សប្តាហ៍មុន', 'group' => 'time'],
            ['key' => 'this_month', 'en' => 'This Month', 'km' => 'ខែនេះ', 'group' => 'time'],
            ['key' => 'last_month', 'en' => 'Last Month', 'km' => 'ខែមុន', 'group' => 'time'],
            ['key' => 'this_year', 'en' => 'This Year', 'km' => 'ឆ្នាំនេះ', 'group' => 'time'],
            ['key' => 'last_year', 'en' => 'Last Year', 'km' => 'ឆ្នាំមុន', 'group' => 'time'],
            
            // Additional common terms
            ['key' => 'total', 'en' => 'Total', 'km' => 'សរុប', 'group' => 'general'],
            ['key' => 'average', 'en' => 'Average', 'km' => 'មធ្យម', 'group' => 'general'],
            ['key' => 'completed', 'en' => 'Completed', 'km' => 'បានបញ្ចប់', 'group' => 'general'],
            ['key' => 'pending', 'en' => 'Pending', 'km' => 'កំពុងរង់ចាំ', 'group' => 'general'],
            ['key' => 'in_progress', 'en' => 'In Progress', 'km' => 'កំពុងដំណើរការ', 'group' => 'general'],
            ['key' => 'view_all', 'en' => 'View All', 'km' => 'មើលទាំងអស់', 'group' => 'general'],
            ['key' => 'view_details', 'en' => 'View Details', 'km' => 'មើលព័ត៌មានលម្អិត', 'group' => 'general'],
            ['key' => 'close', 'en' => 'Close', 'km' => 'បិទ', 'group' => 'general'],
            ['key' => 'open', 'en' => 'Open', 'km' => 'បើក', 'group' => 'general'],
            ['key' => 'print', 'en' => 'Print', 'km' => 'បោះពុម្ព', 'group' => 'general'],
            ['key' => 'reset', 'en' => 'Reset', 'km' => 'កំណត់ឡើងវិញ', 'group' => 'general'],
            ['key' => 'refresh', 'en' => 'Refresh', 'km' => 'ផ្ទុកឡើងវិញ', 'group' => 'general'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                    'group' => $translation['group'],
                    'is_active' => true,
                ]
            );
        }

        // Clear translation cache
        Translation::clearCache();

        $this->command->info('Additional translations seeded successfully!');
        $this->command->info('Total translations added: ' . count($translations));
    }
}