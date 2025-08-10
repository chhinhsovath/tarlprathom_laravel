<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class CoordinatorPageTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Coordinator specific missing translations
            ['key' => 'schools', 'en' => 'Schools', 'km' => 'សាលារៀន', 'group' => 'navigation'],
            ['key' => 'teachers', 'en' => 'Teachers', 'km' => 'គ្រូបង្រៀន', 'group' => 'navigation'],
            ['key' => 'mentors', 'en' => 'Mentors', 'km' => 'អ្នកណែនាំ', 'group' => 'navigation'],
            ['key' => 'total_system_users', 'en' => 'Total System Users', 'km' => 'អ្នកប្រើប្រព័ន្ធសរុប', 'group' => 'coordinator'],
            ['key' => 'teachers_mentors_coordinators', 'en' => 'Teachers + Mentors + Coordinators', 'km' => 'គ្រូ + អ្នកណែនាំ + អ្នកសម្របសម្រួល', 'group' => 'coordinator'],
            ['key' => 'current_language', 'en' => 'Current Language', 'km' => 'ភាសាបច្ចុប្បន្ន', 'group' => 'coordinator'],
            ['key' => 'your_role', 'en' => 'Your Role', 'km' => 'តួនាទីរបស់អ្នក', 'group' => 'coordinator'],
            ['key' => 'data_management_access', 'en' => 'Data Management Access', 'km' => 'សិទ្ធិគ្រប់គ្រងទិន្នន័យ', 'group' => 'coordinator'],
            ['key' => 'switch_language', 'en' => 'Switch Language', 'km' => 'ប្តូរភាសា', 'group' => 'coordinator'],
            ['key' => 'schools_import', 'en' => 'Schools', 'km' => 'សាលារៀន', 'group' => 'import'],
            ['key' => 'teachers_import', 'en' => 'Teachers', 'km' => 'គ្រូបង្រៀន', 'group' => 'import'],
            ['key' => 'mentors_import', 'en' => 'Mentors', 'km' => 'អ្នកណែនាំ', 'group' => 'import'],
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

        $this->command->info('Coordinator page translations seeded successfully!');
        $this->command->info('Total translations added: '.count($translations));
    }
}
