<?php

echo "Starting comprehensive fix for TaRL project...\n";

// Fix 1: Update all blade files with hardcoded Khmer text
$bladesWithKhmer = [
    'resources/views/resources/create.blade.php',
    'resources/views/mentoring/create.blade.php',
    'resources/views/layouts/enhanced-navigation.blade.php',
    'resources/views/showcase.blade.php',
    'resources/views/settings/index.blade.php',
    'resources/views/imports/students.blade.php',
    'resources/views/imports/schools.blade.php',
    'resources/views/imports/users.blade.php',
];

echo "Checking for hardcoded Khmer text in blade files...\n";

foreach ($bladesWithKhmer as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Check if file contains Khmer characters not wrapped in translation functions
        if (preg_match('/[\x{1780}-\x{17FF}]/u', $content)) {
            echo "Found Khmer text in: $file\n";

            // Add common replacements
            $replacements = [
                'ចំណងជើង' => "{{ __('Title') }}",
                'ប្រភេទធនធាន' => "{{ __('Resource Type') }}",
                'ផ្ទុកឯកសារ' => "{{ __('Upload File') }}",
                'តំណវីដេអូ YouTube' => "{{ __('YouTube Video Link') }}",
                'ឯកសារ' => "{{ __('File') }}",
                'ខ្មែរ' => "{{ __('Khmer') }}",
                'បោះបង់' => "{{ __('Cancel') }}",
                'រក្សាទុក' => "{{ __('Save') }}",
                'នាំចូល' => "{{ __('Import') }}",
                'ត្រឡប់' => "{{ __('Back') }}",
            ];

            $updated = false;
            foreach ($replacements as $khmer => $translation) {
                if (strpos($content, $khmer) !== false && strpos($content, '{{ __(') === false) {
                    $content = str_replace($khmer, $translation, $content);
                    $updated = true;
                }
            }

            if ($updated) {
                file_put_contents($file, $content);
                echo "  - Updated $file with translation functions\n";
            }
        }
    }
}

// Fix 2: Add missing translations to language files
echo "\nAdding missing translations to language files...\n";

$enTranslations = json_decode(file_get_contents('lang/en.json'), true) ?: [];
$kmTranslations = json_decode(file_get_contents('lang/km.json'), true) ?: [];

$missingTranslations = [
    'Title' => 'ចំណងជើង',
    'Resource Type' => 'ប្រភេទធនធាន',
    'Upload File' => 'ផ្ទុកឯកសារ',
    'YouTube Video Link' => 'តំណវីដេអូ YouTube',
    'File' => 'ឯកសារ',
    'Khmer' => 'ខ្មែរ',
    'Cancel' => 'បោះបង់',
    'Save' => 'រក្សាទុក',
    'Import' => 'នាំចូល',
    'Back' => 'ត្រឡប់',
    'Dashboard' => 'ផ្ទាំងគ្រប់គ្រង',
    'Students' => 'សិស្ស',
    'Assessments' => 'ការវាយតម្លៃ',
    'Reports' => 'របាយការណ៍',
    'Schools' => 'សាលារៀន',
    'Classes' => 'ថ្នាក់រៀន',
    'Settings' => 'ការកំណត់',
    'Profile' => 'ប្រវត្តិរូប',
    'Log Out' => 'ចេញ',
    'Search' => 'ស្វែងរក',
    'Filter' => 'ច្រោះ',
    'Actions' => 'សកម្មភាព',
    'Edit' => 'កែសម្រួល',
    'Delete' => 'លុប',
    'Create' => 'បង្កើត',
    'Add' => 'បន្ថែម',
    'Submit' => 'ដាក់ស្នើ',
    'Export' => 'នាំចេញ',
    'Download' => 'ទាញយក',
    'Upload' => 'ផ្ទុកឡើង',
    'Select' => 'ជ្រើសរើស',
    'View' => 'មើល',
    'Name' => 'ឈ្មោះ',
    'Email' => 'អ៊ីមែល',
    'Phone' => 'ទូរស័ព្ទ',
    'Address' => 'អាសយដ្ឋាន',
    'Status' => 'ស្ថានភាព',
    'Date' => 'កាលបរិច្ឆេទ',
    'Time' => 'ពេលវេលា',
    'Total' => 'សរុប',
    'Results' => 'លទ្ធផល',
    'Performance' => 'ការអនុវត្ត',
    'Progress' => 'វឌ្ឍនភាព',
    'Baseline' => 'មូលដ្ឋាន',
    'Midline' => 'ពាក់កណ្តាល',
    'Endline' => 'ចុងក្រោយ',
];

foreach ($missingTranslations as $en => $km) {
    if (! isset($enTranslations[$en])) {
        $enTranslations[$en] = $en;
    }
    if (! isset($kmTranslations[$en])) {
        $kmTranslations[$en] = $km;
    }
}

file_put_contents('lang/en.json', json_encode($enTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents('lang/km.json', json_encode($kmTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Updated translation files with '.count($missingTranslations)." translations\n";

// Fix 3: Database column consistency check
echo "\nChecking database column consistency...\n";

$dbChecks = [
    'users' => ['pilot_school_id'],
    'classes' => ['pilot_school_id'],
    'students' => ['pilot_school_id'],
];

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach ($dbChecks as $table => $columns) {
    foreach ($columns as $column) {
        $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        if (! $hasColumn) {
            echo "WARNING: Table '$table' is missing column '$column'\n";
        } else {
            echo "✓ Table '$table' has column '$column'\n";
        }
    }
}

echo "\nFix script completed!\n";
echo "Next steps:\n";
echo "1. Run: php artisan config:clear\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan view:clear\n";
echo "4. Test the application\n";
