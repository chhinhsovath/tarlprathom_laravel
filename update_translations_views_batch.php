<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Function to check if text contains Khmer Unicode
function isKhmerUnicode($text)
{
    if (empty($text)) {
        return false;
    }

    // Check if text contains Khmer Unicode range (U+1780 to U+17FF)
    return preg_match('/[\x{1780}-\x{17FF}]/u', $text);
}

// Get all translations in views group that don't have Khmer Unicode in km field
$query = Translation::where('group', 'views')
    ->where(function ($q) {
        $q->whereNull('km')
            ->orWhere('km', '')
            ->orWhere('km', 'LIKE', '%[%')  // Contains brackets (untranslated)
            ->orWhereRaw('km = en');         // km same as en
    });

$total = $query->count();
echo "Total views translations needing Khmer update: {$total}\n\n";

if ($total == 0) {
    echo "All views translations already have Khmer Unicode!\n";
    exit;
}

// Process in batches of 200
$batchSize = 200;
$processed = 0;

// Views group translations mapping
$viewsTranslations = [
    // Common view elements
    'Welcome' => 'សូមស្វាគមន៍',
    'Welcome to' => 'សូមស្វាគមន៍មកកាន់',
    'Welcome back' => 'សូមស្វាគមន៍ការត្រឡប់មកវិញ',
    'Hello' => 'សួស្តី',
    'Hi' => 'សួស្តី',
    'Good morning' => 'អរុណសួស្តី',
    'Good afternoon' => 'សួស្តីពេលរសៀល',
    'Good evening' => 'សួស្តីពេលល្ងាច',
    'Goodbye' => 'លាហើយ',
    'See you later' => 'ជួបគ្នាពេលក្រោយ',
    'Thank you' => 'អរគុណ',
    'Thanks' => 'អរគុណ',
    'Please' => 'សូម',
    'Sorry' => 'សូមទោស',
    'Excuse me' => 'សូមអត់ទោស',

    // Page titles
    'Home Page' => 'ទំព័រដើម',
    'Dashboard Page' => 'ទំព័រផ្ទាំងគ្រប់គ្រង',
    'Login Page' => 'ទំព័រចូល',
    'Register Page' => 'ទំព័រចុះឈ្មោះ',
    'Profile Page' => 'ទំព័រប្រវត្តិរូប',
    'Settings Page' => 'ទំព័រការកំណត់',
    'Users Page' => 'ទំព័រអ្នកប្រើប្រាស់',
    'Schools Page' => 'ទំព័រសាលារៀន',
    'Students Page' => 'ទំព័រសិស្ស',
    'Assessments Page' => 'ទំព័រការវាយតម្លៃ',
    'Reports Page' => 'ទំព័ររបាយការណ៍',
    'Help Page' => 'ទំព័រជំនួយ',
    'About Page' => 'ទំព័រអំពី',
    'Contact Page' => 'ទំព័រទំនាក់ទំនង',

    // Headers and sections
    'User Management' => 'ការគ្រប់គ្រងអ្នកប្រើប្រាស់',
    'School Management' => 'ការគ្រប់គ្រងសាលារៀន',
    'Student Management' => 'ការគ្រប់គ្រងសិស្ស',
    'Assessment Management' => 'ការគ្រប់គ្រងការវាយតម្លៃ',
    'Report Management' => 'ការគ្រប់គ្រងរបាយការណ៍',
    'System Settings' => 'ការកំណត់ប្រព័ន្ធ',
    'Account Settings' => 'ការកំណត់គណនី',
    'Profile Settings' => 'ការកំណត់ប្រវត្តិរូប',
    'Security Settings' => 'ការកំណត់សុវត្ថិភាព',
    'Notification Settings' => 'ការកំណត់ការជូនដំណឹង',
    'Language Settings' => 'ការកំណត់ភាសា',
    'Theme Settings' => 'ការកំណត់រូបរាង',
    'Privacy Settings' => 'ការកំណត់ឯកជនភាព',

    // User interface elements
    'Main Menu' => 'ម៉ឺនុយមេ',
    'Side Menu' => 'ម៉ឺនុយចំហៀង',
    'Top Menu' => 'ម៉ឺនុយខាងលើ',
    'Breadcrumb' => 'ផ្លូវរុករក',
    'Search Bar' => 'របារស្វែងរក',
    'Filter Bar' => 'របារចម្រាញ់',
    'Toolbar' => 'របារឧបករណ៍',
    'Status Bar' => 'របារស្ថានភាព',
    'Progress Bar' => 'របារវឌ្ឍនភាព',
    'Loading Bar' => 'របារផ្ទុក',
    'Navigation Bar' => 'របាររុករក',
    'Tab Bar' => 'របារផ្ទាំង',
    'Action Bar' => 'របារសកម្មភាព',

    // Content sections
    'Overview' => 'ទិដ្ឋភាពទូទៅ',
    'Summary' => 'សង្ខេប',
    'Details' => 'ព័ត៌មានលម្អិត',
    'Information' => 'ព័ត៌មាន',
    'Statistics' => 'ស្ថិតិ',
    'Analytics' => 'វិភាគ',
    'Charts' => 'គំនូសតាង',
    'Graphs' => 'ក្រាហ្វ',
    'Tables' => 'តារាង',
    'Lists' => 'បញ្ជី',
    'Cards' => 'កាត',
    'Tiles' => 'ក្រឡា',
    'Widgets' => 'ធាតុក្រាហ្វិក',

    // Actions and operations
    'Create New' => 'បង្កើតថ្មី',
    'Add New' => 'បន្ថែមថ្មី',
    'Edit Record' => 'កែសម្រួលកំណត់ត្រា',
    'Update Record' => 'ធ្វើបច្ចុប្បន្នភាពកំណត់ត្រា',
    'Delete Record' => 'លុបកំណត់ត្រា',
    'View Record' => 'មើលកំណត់ត្រា',
    'Save Record' => 'រក្សាទុកកំណត់ត្រា',
    'Cancel Operation' => 'បោះបង់ប្រតិបត្តិការ',
    'Confirm Operation' => 'បញ្ជាក់ប្រតិបត្តិការ',
    'Reset Form' => 'កំណត់ទម្រង់ឡើងវិញ',
    'Submit Form' => 'ដាក់ស្នើទម្រង់',
    'Clear Form' => 'សម្អាតទម្រង់',
    'Validate Form' => 'ផ្ទៀងផ្ទាត់ទម្រង់',

    // Lists and tables
    'List of Users' => 'បញ្ជីអ្នកប្រើប្រាស់',
    'List of Schools' => 'បញ្ជីសាលារៀន',
    'List of Students' => 'បញ្ជីសិស្ស',
    'List of Teachers' => 'បញ្ជីគ្រូ',
    'List of Assessments' => 'បញ្ជីការវាយតម្លៃ',
    'List of Reports' => 'បញ្ជីរបាយការណ៍',
    'List of Classes' => 'បញ្ជីថ្នាក់រៀន',
    'List of Subjects' => 'បញ្ជីមុខវិជ្ជា',
    'List of Grades' => 'បញ្ជីថ្នាក់',
    'List of Scores' => 'បញ្ជីពិន្ទុ',
    'List of Activities' => 'បញ្ជីសកម្មភាព',
    'List of Events' => 'បញ្ជីព្រឹត្តិការណ៍',
    'List of Notifications' => 'បញ្ជីការជូនដំណឹង',

    // Forms and inputs
    'User Form' => 'ទម្រង់អ្នកប្រើប្រាស់',
    'School Form' => 'ទម្រង់សាលា',
    'Student Form' => 'ទម្រង់សិស្ស',
    'Assessment Form' => 'ទម្រង់វាយតម្លៃ',
    'Login Form' => 'ទម្រង់ចូល',
    'Registration Form' => 'ទម្រង់ចុះឈ្មោះ',
    'Contact Form' => 'ទម្រង់ទំនាក់ទំនង',
    'Feedback Form' => 'ទម្រង់មតិយោបល់',
    'Search Form' => 'ទម្រង់ស្វែងរក',
    'Filter Form' => 'ទម្រង់ចម្រាញ់',
    'Upload Form' => 'ទម្រង់ផ្ទុកឡើង',
    'Import Form' => 'ទម្រង់នាំចូល',
    'Export Form' => 'ទម្រង់នាំចេញ',

    // Buttons and links
    'Click here' => 'ចុចទីនេះ',
    'Learn more' => 'ស្វែងយល់បន្ថែម',
    'Read more' => 'អានបន្ថែម',
    'Show more' => 'បង្ហាញបន្ថែម',
    'Show less' => 'បង្ហាញតិច',
    'Go back' => 'ត្រឡប់ក្រោយ',
    'Go forward' => 'ទៅមុខ',
    'Go to' => 'ទៅកាន់',
    'Return to' => 'ត្រឡប់ទៅ',
    'Continue' => 'បន្ត',
    'Skip' => 'រំលង',
    'Finish' => 'បញ្ចប់',
    'Start' => 'ចាប់ផ្តើម',
    'Stop' => 'ឈប់',
    'Pause' => 'ផ្អាក',
    'Resume' => 'បន្ត',
    'Retry' => 'ព្យាយាមម្តងទៀត',
    'Reload' => 'ផ្ទុកឡើងវិញ',
    'Refresh' => 'ធ្វើឱ្យស្រស់',

    // Status indicators
    'Online' => 'នៅលើបណ្តាញ',
    'Offline' => 'ក្រៅបណ្តាញ',
    'Available' => 'មាន',
    'Unavailable' => 'មិនមាន',
    'Connected' => 'បានភ្ជាប់',
    'Disconnected' => 'បានផ្តាច់',
    'Enabled' => 'បានបើក',
    'Disabled' => 'បានបិទ',
    'Visible' => 'មើលឃើញ',
    'Hidden' => 'លាក់',
    'Public' => 'សាធារណៈ',
    'Private' => 'ឯកជន',
    'Shared' => 'បានចែករំលែក',
    'Locked' => 'បានចាក់សោ',
    'Unlocked' => 'បានដោះសោ',

    // Time-related
    'Last updated' => 'ធ្វើបច្ចុប្បន្នភាពចុងក្រោយ',
    'Last modified' => 'កែប្រែចុងក្រោយ',
    'Last accessed' => 'ចូលប្រើចុងក្រោយ',
    'Created on' => 'បង្កើតនៅ',
    'Updated on' => 'ធ្វើបច្ចុប្បន្នភាពនៅ',
    'Expires on' => 'ផុតកំណត់នៅ',
    'Valid until' => 'មានសុពលភាពដល់',
    'Due date' => 'ថ្ងៃកំណត់',
    'Start time' => 'ពេលចាប់ផ្តើម',
    'End time' => 'ពេលបញ្ចប់',
    'Duration' => 'រយៈពេល',
    'Time left' => 'ពេលនៅសល់',
    'Time elapsed' => 'ពេលវេលាកន្លងផុត',

    // Help and support
    'Need help?' => 'ត្រូវការជំនួយ?',
    'Get help' => 'ទទួលជំនួយ',
    'Help Center' => 'មជ្ឈមណ្ឌលជំនួយ',
    'Support Center' => 'មជ្ឈមណ្ឌលគាំទ្រ',
    'Contact Support' => 'ទាក់ទងផ្នែកគាំទ្រ',
    'Documentation' => 'ឯកសារ',
    'User Guide' => 'មគ្គុទ្ទេសក៍អ្នកប្រើ',
    'Tutorial' => 'មេរៀន',
    'FAQ' => 'សំណួរញឹកញាប់',
    'Knowledge Base' => 'មូលដ្ឋានចំណេះដឹង',
    'Community' => 'សហគមន៍',
    'Forum' => 'វេទិកា',
    'Blog' => 'ប្លុក',
    'News' => 'ព័ត៌មាន',
    'Updates' => 'បច្ចុប្បន្នភាព',

    // Empty states
    'No data' => 'គ្មានទិន្នន័យ',
    'No results' => 'គ្មានលទ្ធផល',
    'No records' => 'គ្មានកំណត់ត្រា',
    'No items' => 'គ្មានធាតុ',
    'No entries' => 'គ្មានធាតុ',
    'No matches' => 'គ្មានការផ្គូផ្គង',
    'Nothing to show' => 'គ្មានអ្វីត្រូវបង្ហាញ',
    'Empty list' => 'បញ្ជីទទេ',
    'Empty table' => 'តារាងទទេ',
    'Empty folder' => 'ថតទទេ',
    'Empty cart' => 'រទេះទទេ',
    'Empty inbox' => 'ប្រអប់ទទួលទទេ',

    // Error states
    'Error occurred' => 'កំហុសបានកើតឡើង',
    'Something went wrong' => 'មានអ្វីមួយខុសប្រក្រតី',
    'Page not found' => 'រកមិនឃើញទំព័រ',
    'Access denied' => 'ការចូលប្រើត្រូវបានបដិសេធ',
    'Permission denied' => 'សិទ្ធិត្រូវបានបដិសេធ',
    'Invalid request' => 'សំណើមិនត្រឹមត្រូវ',
    'Session expired' => 'វគ្គបានផុតកំណត់',
    'Connection lost' => 'បាត់បង់ការតភ្ជាប់',
    'Server error' => 'កំហុសម៉ាស៊ីនមេ',
    'Network error' => 'កំហុសបណ្តាញ',
    'Timeout error' => 'កំហុសអស់ពេល',
    'Unknown error' => 'កំហុសមិនស្គាល់',

    // Success states
    'Success!' => 'ជោគជ័យ!',
    'Well done!' => 'ល្អណាស់!',
    'Great job!' => 'ការងារដ៏អស្ចារ្យ!',
    'Completed!' => 'បានបញ្ចប់!',
    'Saved!' => 'បានរក្សាទុក!',
    'Updated!' => 'បានធ្វើបច្ចុប្បន្នភាព!',
    'Deleted!' => 'បានលុប!',
    'Submitted!' => 'បានដាក់ស្នើ!',
    'Sent!' => 'បានផ្ញើ!',
    'Done!' => 'រួចរាល់!',
    'Finished!' => 'បានបញ្ចប់!',
    'Congratulations!' => 'សូមអបអរសាទរ!',
];

// Process translations in batches
while ($processed < $total) {
    $batch = Translation::where('group', 'views')
        ->where(function ($q) {
            $q->whereNull('km')
                ->orWhere('km', '')
                ->orWhere('km', 'LIKE', '%[%')
                ->orWhereRaw('km = en');
        })
        ->limit($batchSize)
        ->get();

    echo 'Processing batch: '.($processed + 1).' to '.min($processed + $batchSize, $total)."\n";

    foreach ($batch as $translation) {
        // Check if we have a Khmer translation for this key
        if (isset($viewsTranslations[$translation->key])) {
            $translation->km = $viewsTranslations[$translation->key];
            $translation->save();
            echo "  ✓ Updated: {$translation->key}\n";
        } else {
            // Try to find a similar key
            $found = false;
            foreach ($viewsTranslations as $en => $km) {
                if (stripos($translation->key, $en) !== false || stripos($en, $translation->key) !== false) {
                    $translation->km = $km;
                    $translation->save();
                    echo "  ✓ Updated (similar): {$translation->key} -> {$km}\n";
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                echo "  ⚠ No translation found for: {$translation->key}\n";
            }
        }

        $processed++;
    }

    echo "\n";

    // Clear cache periodically
    if ($processed % 400 == 0) {
        Translation::clearCache();
        echo "Cache cleared.\n\n";
    }
}

// Final cache clear
Translation::clearCache();

// Check remaining untranslated
$remaining = Translation::where('group', 'views')
    ->where(function ($q) {
        $q->whereNull('km')
            ->orWhere('km', '')
            ->orWhere('km', 'LIKE', '%[%')
            ->orWhereRaw('km = en');
    })
    ->count();

echo "\n=== Summary ===\n";
echo "Total processed: {$processed}\n";
echo "Remaining untranslated: {$remaining}\n";

if ($remaining > 0) {
    echo "\nSome translations still need Khmer text. Running another pass...\n";
}
