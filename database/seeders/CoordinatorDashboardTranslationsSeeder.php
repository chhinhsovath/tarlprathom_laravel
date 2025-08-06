<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class CoordinatorDashboardTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Coordinator Dashboard specific
            ['key' => 'coordinator_dashboard', 'en' => 'Coordinator Dashboard', 'km' => 'ផ្ទាំងគ្រប់គ្រងអ្នកសម្របសម្រួល', 'group' => 'coordinator'],
            ['key' => 'coordinator_workspace', 'en' => 'Coordinator Workspace', 'km' => 'កន្លែងធ្វើការអ្នកសម្របសម្រួល', 'group' => 'coordinator'],
            ['key' => 'system_overview_and_data_management_center', 'en' => 'System overview and data management center', 'km' => 'ទិដ្ឋភាពទូទៅនៃប្រព័ន្ធ និងមជ្ឈមណ្ឌលគ្រប់គ្រងទិន្នន័យ', 'group' => 'coordinator'],
            
            // Stats labels
            ['key' => 'total_registered', 'en' => 'Total registered', 'km' => 'ចុះឈ្មោះសរុប', 'group' => 'stats'],
            ['key' => 'active_accounts', 'en' => 'Active accounts', 'km' => 'គណនីសកម្ម', 'group' => 'stats'],
            ['key' => 'coordinators', 'en' => 'Coordinators', 'km' => 'អ្នកសម្របសម្រួល', 'group' => 'navigation'],
            
            // Activity labels
            ['key' => 'this_month_activity', 'en' => 'This Month Activity', 'km' => 'សកម្មភាពខែនេះ', 'group' => 'activity'],
            ['key' => 'today_activity', 'en' => 'Today Activity', 'km' => 'សកម្មភាពថ្ងៃនេះ', 'group' => 'activity'],
            ['key' => 'schools_added', 'en' => 'Schools Added', 'km' => 'សាលាបានបន្ថែម', 'group' => 'activity'],
            ['key' => 'users_added', 'en' => 'Users Added', 'km' => 'អ្នកប្រើបានបន្ថែម', 'group' => 'activity'],
            
            // Data Import Center
            ['key' => 'data_import_center', 'en' => 'Data Import Center', 'km' => 'មជ្ឈមណ្ឌលនាំចូលទិន្នន័យ', 'group' => 'import'],
            ['key' => 'schools_import', 'en' => 'Schools', 'km' => 'សាលារៀន', 'group' => 'import'],
            ['key' => 'teachers_import', 'en' => 'Teachers', 'km' => 'គ្រូបង្រៀន', 'group' => 'import'],
            ['key' => 'mentors_import', 'en' => 'Mentors', 'km' => 'អ្នកណែនាំ', 'group' => 'import'],
            ['key' => 'templates', 'en' => 'Templates', 'km' => 'គំរូ', 'group' => 'import'],
            
            // Language Center
            ['key' => 'language_center', 'en' => 'Language Center', 'km' => 'មជ្ឈមណ្ឌលភាសា', 'group' => 'language'],
            ['key' => 'available_languages', 'en' => 'Available Languages', 'km' => 'ភាសាដែលមាន', 'group' => 'language'],
            ['key' => 'current', 'en' => 'Current', 'km' => 'បច្ចុប្បន្ន', 'group' => 'language'],
            ['key' => 'switch', 'en' => 'Switch', 'km' => 'ប្តូរ', 'group' => 'language'],
            ['key' => 'translation_tools', 'en' => 'Translation Tools', 'km' => 'ឧបករណ៍បកប្រែ', 'group' => 'language'],
            ['key' => 'open_translation_editor', 'en' => 'Open Translation Editor', 'km' => 'បើកកម្មវិធីកែសម្រួលការបកប្រែ', 'group' => 'language'],
            
            // Quick Download
            ['key' => 'quick_download', 'en' => 'Quick Download', 'km' => 'ទាញយករហ័ស', 'group' => 'download'],
            ['key' => 'schools_template', 'en' => 'Schools Template', 'km' => 'គំរូសាលារៀន', 'group' => 'download'],
            ['key' => 'teachers_template', 'en' => 'Teachers Template', 'km' => 'គំរូគ្រូបង្រៀន', 'group' => 'download'],
            ['key' => 'mentors_template', 'en' => 'Mentors Template', 'km' => 'គំរូអ្នកណែនាំ', 'group' => 'download'],
            
            // Navigation items
            ['key' => 'plp', 'en' => 'PLP', 'km' => 'PLP', 'group' => 'navigation'],
            ['key' => 'administration', 'en' => 'Administration', 'km' => 'រដ្ឋបាល', 'group' => 'navigation'],
            ['key' => 'mentoring', 'en' => 'Mentoring', 'km' => 'ការណែនាំ', 'group' => 'navigation'],
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

        $this->command->info('Coordinator Dashboard translations seeded successfully!');
        $this->command->info('Total translations added: ' . count($translations));
    }
}