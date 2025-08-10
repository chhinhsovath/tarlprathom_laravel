<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class NavigationTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Main navigation items
            ['key' => 'tarl_project', 'en' => 'TaRL Project', 'km' => 'គម្រោង TaRL', 'group' => 'navigation'],
            ['key' => 'plp', 'en' => 'PLP', 'km' => 'PLP', 'group' => 'navigation'],
            ['key' => 'dashboard', 'en' => 'Dashboard', 'km' => 'ផ្ទាំងគ្រប់គ្រង', 'group' => 'navigation'],
            ['key' => 'assessments', 'en' => 'Assessments', 'km' => 'ការវាយតម្លៃ', 'group' => 'navigation'],
            ['key' => 'students', 'en' => 'Students', 'km' => 'សិស្ស', 'group' => 'navigation'],
            ['key' => 'mentoring', 'en' => 'Mentoring', 'km' => 'ការណែនាំ', 'group' => 'navigation'],
            ['key' => 'reports', 'en' => 'Reports', 'km' => 'របាយការណ៍', 'group' => 'navigation'],
            ['key' => 'coordinator_workspace', 'en' => 'Coordinator Workspace', 'km' => 'កន្លែងធ្វើការអ្នកសម្របសម្រួល', 'group' => 'navigation'],
            ['key' => 'administration', 'en' => 'Administration', 'km' => 'រដ្ឋបាល', 'group' => 'navigation'],

            // User roles
            ['key' => 'admin', 'en' => 'Admin', 'km' => 'អ្នកគ្រប់គ្រង', 'group' => 'role'],
            ['key' => 'teacher', 'en' => 'Teacher', 'km' => 'គ្រូបង្រៀន', 'group' => 'role'],
            ['key' => 'mentor', 'en' => 'Mentor', 'km' => 'អ្នកណែនាំ', 'group' => 'role'],
            ['key' => 'coordinator', 'en' => 'Coordinator', 'km' => 'អ្នកសម្របសម្រួល', 'group' => 'role'],
            ['key' => 'viewer', 'en' => 'Viewer', 'km' => 'អ្នកមើល', 'group' => 'role'],

            // Profile menu items
            ['key' => 'my_profile', 'en' => 'My Profile', 'km' => 'ប្រវត្តិរូបរបស់ខ្ញុំ', 'group' => 'profile'],
            ['key' => 'change_password', 'en' => 'Change Password', 'km' => 'ផ្លាស់ប្តូរពាក្យសម្ងាត់', 'group' => 'profile'],
            ['key' => 'help_support', 'en' => 'Help & Support', 'km' => 'ជំនួយ និងការគាំទ្រ', 'group' => 'profile'],
            ['key' => 'about', 'en' => 'About', 'km' => 'អំពី', 'group' => 'profile'],
            ['key' => 'sign_out', 'en' => 'Sign Out', 'km' => 'ចាកចេញ', 'group' => 'profile'],
            ['key' => 'language', 'en' => 'Language', 'km' => 'ភាសា', 'group' => 'profile'],

            // Common UI elements
            ['key' => 'search', 'en' => 'Search', 'km' => 'ស្វែងរក', 'group' => 'general'],
            ['key' => 'filter', 'en' => 'Filter', 'km' => 'ត្រង', 'group' => 'general'],
            ['key' => 'export', 'en' => 'Export', 'km' => 'នាំចេញ', 'group' => 'general'],
            ['key' => 'import', 'en' => 'Import', 'km' => 'នាំចូល', 'group' => 'general'],
            ['key' => 'add', 'en' => 'Add', 'km' => 'បន្ថែម', 'group' => 'general'],
            ['key' => 'edit', 'en' => 'Edit', 'km' => 'កែសម្រួល', 'group' => 'general'],
            ['key' => 'delete', 'en' => 'Delete', 'km' => 'លុប', 'group' => 'general'],
            ['key' => 'save', 'en' => 'Save', 'km' => 'រក្សាទុក', 'group' => 'general'],
            ['key' => 'cancel', 'en' => 'Cancel', 'km' => 'បោះបង់', 'group' => 'general'],
            ['key' => 'back', 'en' => 'Back', 'km' => 'ត្រឡប់', 'group' => 'general'],
            ['key' => 'next', 'en' => 'Next', 'km' => 'បន្ទាប់', 'group' => 'general'],
            ['key' => 'previous', 'en' => 'Previous', 'km' => 'មុន', 'group' => 'general'],
            ['key' => 'submit', 'en' => 'Submit', 'km' => 'ដាក់ស្នើ', 'group' => 'general'],
            ['key' => 'close', 'en' => 'Close', 'km' => 'បិទ', 'group' => 'general'],
            ['key' => 'view', 'en' => 'View', 'km' => 'មើល', 'group' => 'general'],
            ['key' => 'download', 'en' => 'Download', 'km' => 'ទាញយក', 'group' => 'general'],
            ['key' => 'upload', 'en' => 'Upload', 'km' => 'ផ្ទុកឡើង', 'group' => 'general'],
            ['key' => 'refresh', 'en' => 'Refresh', 'km' => 'ផ្ទុកឡើងវិញ', 'group' => 'general'],
            ['key' => 'print', 'en' => 'Print', 'km' => 'បោះពុម្ព', 'group' => 'general'],
            ['key' => 'settings', 'en' => 'Settings', 'km' => 'ការកំណត់', 'group' => 'general'],
            ['key' => 'logout', 'en' => 'Logout', 'km' => 'ចាកចេញ', 'group' => 'general'],
            ['key' => 'login', 'en' => 'Login', 'km' => 'ចូល', 'group' => 'general'],
            ['key' => 'register', 'en' => 'Register', 'km' => 'ចុះឈ្មោះ', 'group' => 'general'],

            // Status messages
            ['key' => 'loading', 'en' => 'Loading...', 'km' => 'កំពុងផ្ទុក...', 'group' => 'status'],
            ['key' => 'processing', 'en' => 'Processing...', 'km' => 'កំពុងដំណើរការ...', 'group' => 'status'],
            ['key' => 'success', 'en' => 'Success', 'km' => 'ជោគជ័យ', 'group' => 'status'],
            ['key' => 'error', 'en' => 'Error', 'km' => 'កំហុស', 'group' => 'status'],
            ['key' => 'warning', 'en' => 'Warning', 'km' => 'ការព្រមាន', 'group' => 'status'],
            ['key' => 'info', 'en' => 'Info', 'km' => 'ព័ត៌មាន', 'group' => 'status'],

            // Table headers
            ['key' => 'name', 'en' => 'Name', 'km' => 'ឈ្មោះ', 'group' => 'table'],
            ['key' => 'email', 'en' => 'Email', 'km' => 'អ៊ីមែល', 'group' => 'table'],
            ['key' => 'phone', 'en' => 'Phone', 'km' => 'ទូរស័ព្ទ', 'group' => 'table'],
            ['key' => 'address', 'en' => 'Address', 'km' => 'អាសយដ្ឋាន', 'group' => 'table'],
            ['key' => 'actions', 'en' => 'Actions', 'km' => 'សកម្មភាព', 'group' => 'table'],
            ['key' => 'created_at', 'en' => 'Created At', 'km' => 'បង្កើតនៅ', 'group' => 'table'],
            ['key' => 'updated_at', 'en' => 'Updated At', 'km' => 'ធ្វើបច្ចុប្បន្នភាពនៅ', 'group' => 'table'],

            // Forms
            ['key' => 'first_name', 'en' => 'First Name', 'km' => 'នាមខ្លួន', 'group' => 'form'],
            ['key' => 'last_name', 'en' => 'Last Name', 'km' => 'នាមត្រកូល', 'group' => 'form'],
            ['key' => 'gender', 'en' => 'Gender', 'km' => 'ភេទ', 'group' => 'form'],
            ['key' => 'male', 'en' => 'Male', 'km' => 'ប្រុស', 'group' => 'form'],
            ['key' => 'female', 'en' => 'Female', 'km' => 'ស្រី', 'group' => 'form'],
            ['key' => 'date_of_birth', 'en' => 'Date of Birth', 'km' => 'ថ្ងៃខែឆ្នាំកំណើត', 'group' => 'form'],
            ['key' => 'select', 'en' => 'Select', 'km' => 'ជ្រើសរើស', 'group' => 'form'],
            ['key' => 'choose', 'en' => 'Choose', 'km' => 'ជ្រើសរើស', 'group' => 'form'],
            ['key' => 'required', 'en' => 'Required', 'km' => 'ចាំបាច់', 'group' => 'form'],
            ['key' => 'optional', 'en' => 'Optional', 'km' => 'ស្រេចចិត្ត', 'group' => 'form'],

            // Geographic
            ['key' => 'province', 'en' => 'Province', 'km' => 'ខេត្ត', 'group' => 'geographic'],
            ['key' => 'district', 'en' => 'District', 'km' => 'ស្រុក', 'group' => 'geographic'],
            ['key' => 'commune', 'en' => 'Commune', 'km' => 'ឃុំ', 'group' => 'geographic'],
            ['key' => 'village', 'en' => 'Village', 'km' => 'ភូមិ', 'group' => 'geographic'],
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

        $this->command->info('Navigation translations seeded successfully!');
        $this->command->info('Total translations added: '.count($translations));
    }
}
