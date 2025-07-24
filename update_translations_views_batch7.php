<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Batch 7: Help documentation and instructional content
$batch7Translations = [
    // Help instructions
    'How to Log In' => 'របៀបចូលប្រើប្រាស់',
    'Steps:' => 'ជំហាន៖',
    'Go to the login page' => 'ទៅកាន់ទំព័រចូលប្រើ',
    'Enter your email address' => 'បញ្ចូលអាសយដ្ឋានអ៊ីមែលរបស់អ្នក',
    'Enter your password' => 'បញ្ចូលពាក្យសម្ងាត់របស់អ្នក',
    'Click the login button' => 'ចុចប៊ូតុងចូលប្រើ',
    'Update Your Profile' => 'ធ្វើបច្ចុប្បន្នភាពប្រវត្តិរូបរបស់អ្នក',
    'Click your name in the top right corner' => 'ចុចឈ្មោះរបស់អ្នកនៅជ្រុងខាងស្តាំខាងលើ',
    'Update your information:' => 'ធ្វើបច្ចុប្បន្នភាពព័ត៌មានរបស់អ្នក៖',
    'Profile photo (max 5MB)' => 'រូបថតប្រវត្តិរូប (អតិបរមា 5MB)',
    
    // Student management instructions
    'Add a New Student' => 'បន្ថែមសិស្សថ្មី',
    'Fill in required information:' => 'បំពេញព័ត៌មានដែលត្រូវការ៖',
    'Student ID' => 'អត្តលេខសិស្ស',
    'Grade (4 or 5 only)' => 'ថ្នាក់ (តែថ្នាក់ទី៤ ឬ ទី៥ប៉ុណ្ណោះ)',
    'Class/Section' => 'ថ្នាក់/ផ្នែក',
    'Upload student photo (optional)' => 'ផ្ទុករូបថតសិស្សឡើង (ស្រេចចិត្ត)',
    'Important: Only Grade 4 and 5 students can be added' => 'សំខាន់៖ អាចបន្ថែមតែសិស្សថ្នាក់ទី៤ និងទី៥ប៉ុណ្ណោះ',
    
    // Assessment instructions
    'Select Students for Assessments' => 'ជ្រើសរើសសិស្សសម្រាប់ការវាយតម្លៃ',
    'For Midline/Endline Assessments:' => 'សម្រាប់ការវាយតម្លៃពាក់កណ្តាល/ចុងក្រោយ៖',
    'Only students who completed previous assessments are eligible' => 'មានតែសិស្សដែលបានបញ្ចប់ការវាយតម្លៃមុនៗប៉ុណ្ណោះដែលមានសិទ្ធិ',
    'Students must progress through levels sequentially' => 'សិស្សត្រូវតែឆ្លងកាត់កម្រិតតាមលំដាប់',
    
    // No access messages
    'No editing rights' => 'គ្មានសិទ្ធិកែសម្រួល',
    'View only access' => 'ការចូលប្រើមើលតែប៉ុណ្ណោះ',
    'Contact administrator for access' => 'ទាក់ទងអ្នកគ្រប់គ្រងសម្រាប់ការចូលប្រើ',
    'You do not have permission to perform this action' => 'អ្នកមិនមានសិទ្ធិអនុវត្តសកម្មភាពនេះទេ',
    'This feature is not available for your role' => 'មុខងារនេះមិនមានសម្រាប់តួនាទីរបស់អ្នកទេ',
    
    // Assessment levels explanation
    'Assessment Levels Explained' => 'ការពន្យល់អំពីកម្រិតវាយតម្លៃ',
    'Khmer Reading Levels:' => 'កម្រិតអានភាសាខ្មែរ៖',
    'Beginner - Cannot read letters' => 'ចាប់ផ្តើម - មិនអាចអានអក្សរ',
    'Letter - Can read letters' => 'អក្សរ - អាចអានអក្សរ',
    'Word - Can read words' => 'ពាក្យ - អាចអានពាក្យ',
    'Sentence - Can read sentences' => 'ប្រយោគ - អាចអានប្រយោគ',
    'Paragraph - Can read paragraphs' => 'កថាខណ្ឌ - អាចអានកថាខណ្ឌ',
    'Story - Can read stories' => 'រឿង - អាចអានរឿង',
    'Comprehension 1 - Basic understanding' => 'យល់ដឹង១ - ការយល់ដឹងមូលដ្ឋាន',
    'Comprehension 2 - Advanced understanding' => 'យល់ដឹង២ - ការយល់ដឹងកម្រិតខ្ពស់',
    
    'Math Levels:' => 'កម្រិតគណិតវិទ្យា៖',
    'Beginner - Cannot do basic operations' => 'ចាប់ផ្តើម - មិនអាចធ្វើប្រតិបត្តិការមូលដ្ឋាន',
    '1-Digit - Can add/subtract single digits' => '១ខ្ទង់ - អាចបូក/ដកលេខ១ខ្ទង់',
    '2-Digit - Can add/subtract double digits' => '២ខ្ទង់ - អាចបូក/ដកលេខ២ខ្ទង់',
    'Subtraction - Can perform subtraction' => 'ការដក - អាចធ្វើការដក',
    'Division - Can perform division' => 'ការចែក - អាចធ្វើការចែក',
    'Word Problems - Can solve word problems' => 'ល្បាកលេខ - អាចដោះស្រាយល្បាកលេខ',
    
    // Report descriptions
    'Report Types' => 'ប្រភេទរបាយការណ៍',
    'Available Reports:' => 'របាយការណ៍ដែលមាន៖',
    'Individual student progress over assessment cycles' => 'វឌ្ឍនភាពសិស្សម្នាក់ៗតាមវដ្តវាយតម្លៃ',
    'Class-wise performance comparison' => 'ការប្រៀបធៀបសមិទ្ធផលតាមថ្នាក់',
    'School-level achievement statistics' => 'ស្ថិតិសមិទ្ធិផលកម្រិតសាលា',
    'Subject-wise analysis (Khmer/Math)' => 'ការវិភាគតាមមុខវិជ្ជា (ខ្មែរ/គណិត)',
    'Gender-based performance metrics' => 'រង្វាស់សមិទ្ធផលផ្អែកលើភេទ',
    'Mentor visit impact analysis' => 'ការវិភាគផលប៉ះពាល់នៃការមកសួរសុខទុក្ខរបស់អ្នកណែនាំ',
    
    // Data management
    'Data Management' => 'ការគ្រប់គ្រងទិន្នន័យ',
    'Import/Export Guidelines:' => 'គោលការណ៍ណែនាំនាំចូល/នាំចេញ៖',
    'Always backup data before importing' => 'តែងតែបម្រុងទុកទិន្នន័យមុនពេលនាំចូល',
    'Use provided templates for imports' => 'ប្រើគំរូដែលបានផ្តល់សម្រាប់ការនាំចូល',
    'Verify data accuracy before submission' => 'ផ្ទៀងផ្ទាត់ភាពត្រឹមត្រូវនៃទិន្នន័យមុនពេលដាក់ស្នើ',
    'Export regularly for record keeping' => 'នាំចេញជាទៀងទាត់សម្រាប់ការរក្សាកំណត់ត្រា',
    
    // System requirements
    'System Requirements' => 'តម្រូវការប្រព័ន្ធ',
    'Browser Requirements:' => 'តម្រូវការកម្មវិធីរុករក៖',
    'Chrome (recommended)' => 'Chrome (ណែនាំ)',
    'Firefox' => 'Firefox',
    'Safari' => 'Safari',
    'Edge' => 'Edge',
    'Internet connection required' => 'ត្រូវការការតភ្ជាប់អ៊ីនធឺណិត',
    'JavaScript must be enabled' => 'JavaScript ត្រូវតែបើក',
    
    // Troubleshooting
    'Troubleshooting' => 'ការដោះស្រាយបញ្ហា',
    'Common Issues:' => 'បញ្ហាទូទៅ៖',
    'Cannot log in' => 'មិនអាចចូលប្រើបាន',
    'Check your email and password' => 'ពិនិត្យអ៊ីមែលនិងពាក្យសម្ងាត់របស់អ្នក',
    'Contact admin if locked out' => 'ទាក់ទងអ្នកគ្រប់គ្រងប្រសិនបើត្រូវបានចាក់សោ',
    'Data not saving' => 'ទិន្នន័យមិនរក្សាទុក',
    'Check internet connection' => 'ពិនិត្យការតភ្ជាប់អ៊ីនធឺណិត',
    'Try refreshing the page' => 'សាកល្បងផ្ទុកទំព័រឡើងវិញ',
    'Report errors' => 'កំហុសរបាយការណ៍',
    'Ensure data exists for selected criteria' => 'ត្រូវប្រាកដថាទិន្នន័យមានសម្រាប់លក្ខណៈវិនិច្ឆ័យដែលបានជ្រើសរើស',
    
    // Best practices
    'Best Practices' => 'ការអនុវត្តល្អបំផុត',
    'Assessment Best Practices:' => 'ការអនុវត្តល្អបំផុតសម្រាប់ការវាយតម្លៃ៖',
    'Conduct assessments in quiet environment' => 'ធ្វើការវាយតម្លៃក្នុងបរិយាកាសស្ងៀមស្ងាត់',
    'One-on-one assessment recommended' => 'ការវាយតម្លៃម្តងម្នាក់ត្រូវបានណែនាំ',
    'Save progress frequently' => 'រក្សាទុកវឌ្ឍនភាពញឹកញាប់',
    'Double-check level assignments' => 'ពិនិត្យការកំណត់កម្រិតពីរដង',
    
    // Contact information
    'Need Help?' => 'ត្រូវការជំនួយ?',
    'Contact Information:' => 'ព័ត៌មានទំនាក់ទំនង៖',
    'Technical Support' => 'ជំនួយបច្ចេកទេស',
    'Training Support' => 'ជំនួយការបណ្តុះបណ្តាល',
    'General Inquiries' => 'សំណួរទូទៅ',
    'Support Hours' => 'ម៉ោងគាំទ្រ',
    'Monday to Friday' => 'ថ្ងៃច័ន្ទដល់ថ្ងៃសុក្រ',
    '8:00 AM - 5:00 PM' => '៨:០០ ព្រឹក - ៥:០០ ល្ងាច',
    
    // Additional UI elements
    'Toggle Navigation' => 'បិទបើកការរុករក',
    'Toggle Sidebar' => 'បិទបើករបារចំហៀង',
    'Expand Sidebar' => 'ពង្រីករបារចំហៀង',
    'Collapse Sidebar' => 'បង្រួមរបារចំហៀង',
    'Full Screen' => 'អេក្រង់ពេញ',
    'Exit Full Screen' => 'ចេញពីអេក្រង់ពេញ',
    'Zoom In' => 'ពង្រីក',
    'Zoom Out' => 'បង្រួម',
    'Reset Zoom' => 'កំណត់ការពង្រីកឡើងវិញ',
    'Print Preview' => 'មើលមុនបោះពុម្ព',
    'Page Setup' => 'កំណត់ទំព័រ',
    'Print Options' => 'ជម្រើសបោះពុម្ព',
    
    // Status updates
    'Status Updates' => 'បច្ចុប្បន្នភាពស្ថានភាព',
    'Recent Changes' => 'ការផ្លាស់ប្តូរថ្មីៗ',
    'Upcoming Features' => 'មុខងារនាពេលខាងមុខ',
    'Known Issues' => 'បញ្ហាដែលបានដឹង',
    'Scheduled Maintenance' => 'ការថែទាំដែលបានគ្រោងទុក',
    'System Updates' => 'បច្ចុប្បន្នភាពប្រព័ន្ធ',
    'Release Notes' => 'កំណត់ចំណាំការចេញផ្សាយ',
    'Version History' => 'ប្រវត្តិកំណែ',
    
    // Error handling
    'Error Details' => 'ព័ត៌មានលម្អិតកំហុស',
    'Error Code' => 'លេខកូដកំហុស',
    'Error Message' => 'សារកំហុស',
    'Stack Trace' => 'ដានកំហុស',
    'Report Error' => 'រាយការណ៍កំហុស',
    'Ignore Error' => 'មិនអើពើកំហុស',
    'Retry Operation' => 'ព្យាយាមប្រតិបត្តិការម្តងទៀត',
    
    // Additional fields
    '2024-2025' => '២០២៤-២០២៥',
    '2023-2024' => '២០២៣-២០២៤',
    '2022-2023' => '២០២២-២០២៣',
    'Academic Year' => 'ឆ្នាំសិក្សា',
    'School Year' => 'ឆ្នាំសិក្សា',
    'Term' => 'ត្រីមាស',
    'Semester' => 'ឆមាស',
    'Quarter' => 'ត្រីមាស',
    'Month' => 'ខែ',
    'Week' => 'សប្តាហ៍',
    'Day' => 'ថ្ងៃ',
    'Hour' => 'ម៉ោង',
    'Minute' => 'នាទី',
    'Second' => 'វិនាទី',
];

echo "Updating views translations batch 7...\n";
$count = 0;

foreach ($batch7Translations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->update(['km' => $km]);
    
    if ($updated > 0) {
        echo "  ✓ Updated: {$en}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} translations in batch 7.\n";

// Clear cache
Translation::clearCache();

// Check status
$remaining = Translation::where('group', 'views')
    ->where(function($q) {
        $q->whereNull('km')
          ->orWhere('km', '')
          ->orWhere('km', 'LIKE', '%[%')
          ->orWhereRaw('km = en');
    })
    ->count();

$total = Translation::where('group', 'views')->count();
$completed = $total - $remaining;
$percentage = $total > 0 ? round(($completed / $total) * 100, 2) : 100;

echo "\n=== Views Group Status ===\n";
echo "Total: {$total}\n";
echo "Completed: {$completed}\n";
echo "Remaining: {$remaining}\n";
echo "Completion: {$percentage}%\n";

// Overall status
$overallTotal = Translation::count();
$overallCompleted = Translation::whereNotNull('km')
    ->where('km', '!=', '')
    ->whereRaw('km != en')
    ->where('km', 'NOT LIKE', '%[%')
    ->count();
$overallPercentage = $overallTotal > 0 ? round(($overallCompleted / $overallTotal) * 100, 2) : 0;

echo "\n=== Overall Translation Status ===\n";
echo "Total: {$overallTotal}\n";
echo "Completed: {$overallCompleted}\n";
echo "Overall Completion: {$overallPercentage}%\n";