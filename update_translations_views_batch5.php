<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Batch 5: Help content, user roles, and system documentation
$batch5Translations = [
    // Help documentation
    'Find step-by-step instructions and learn how to use the system effectively.' => 'ស្វែងរកការណែនាំជាជំហានៗ និងរៀនពីរបៀបប្រើប្រាស់ប្រព័ន្ធប្រកបដោយប្រសិទ្ធភាព។',
    'Common Workflows' => 'លំហូរការងារទូទៅ',
    'User Roles & Permissions' => 'តួនាទីនិងសិទ្ធិអ្នកប្រើប្រាស់',
    'Resources' => 'ធនធាន',
    'Bulk Import' => 'នាំចូលច្រើន',

    // User roles descriptions
    'Administrator' => 'អ្នកគ្រប់គ្រង',
    'Full system access' => 'ការចូលប្រើប្រព័ន្ធពេញលេញ',
    'Manage all users' => 'គ្រប់គ្រងអ្នកប្រើប្រាស់ទាំងអស់',
    'Assign mentors to schools' => 'ចាត់តាំងអ្នកណែនាំទៅសាលា',
    'Access all reports' => 'ចូលប្រើរបាយការណ៍ទាំងអស់',
    'Manage own students' => 'គ្រប់គ្រងសិស្សផ្ទាល់ខ្លួន',
    'Conduct assessments' => 'ធ្វើការវាយតម្លៃ',
    'View class reports' => 'មើលរបាយការណ៍ថ្នាក់',
    'Access resources' => 'ចូលប្រើធនធាន',
    'Visit assigned schools' => 'ទស្សនាសាលាដែលបានចាត់តាំង',
    'Verify assessments' => 'ផ្ទៀងផ្ទាត់ការវាយតម្លៃ',
    'Document visits' => 'កត់ត្រាការទស្សនា',
    'View school reports' => 'មើលរបាយការណ៍សាលា',
    'Viewer' => 'អ្នកមើល',
    'View reports only' => 'មើលតែរបាយការណ៍ប៉ុណ្ណោះ',
    'Read-only access' => 'ការចូលប្រើអានតែប៉ុណ្ណោះ',

    // Assessment workflow
    'Assessment Workflow' => 'លំហូរការងារវាយតម្លៃ',
    'Step 1: Select Assessment Type' => 'ជំហានទី១៖ ជ្រើសរើសប្រភេទវាយតម្លៃ',
    'Step 2: Choose Students' => 'ជំហានទី២៖ ជ្រើសរើសសិស្ស',
    'Step 3: Conduct Assessment' => 'ជំហានទី៣៖ ធ្វើការវាយតម្លៃ',
    'Step 4: Submit Results' => 'ជំហានទី៤៖ ដាក់ស្នើលទ្ធផល',
    'Important Notes' => 'ចំណាំសំខាន់',
    'Save your progress regularly' => 'រក្សាទុកវឌ្ឍនភាពរបស់អ្នកជាប្រចាំ',
    'All fields marked with * are required' => 'វាលទាំងអស់ដែលមានសញ្ញា * ចាំបាច់ត្រូវបំពេញ',

    // Import workflow
    'How to Import Data' => 'របៀបនាំចូលទិន្នន័យ',
    'Step 1: Download the template' => 'ជំហានទី១៖ ទាញយកគំរូ',
    'Step 2: Fill in the data' => 'ជំហានទី២៖ បំពេញទិន្នន័យ',
    'Step 3: Upload the file' => 'ជំហានទី៣៖ ផ្ទុកឯកសារឡើង',
    'Step 4: Review and confirm' => 'ជំហានទី៤៖ ពិនិត្យនិងបញ្ជាក់',
    'Download the Excel template' => 'ទាញយកគំរូ Excel',
    'Fill the template with your data' => 'បំពេញគំរូជាមួយទិន្នន័យរបស់អ្នក',
    'Make sure all required fields are filled' => 'ត្រូវប្រាកដថាវាលចាំបាច់ទាំងអស់ត្រូវបានបំពេញ',
    'Upload the completed file' => 'ផ្ទុកឯកសារដែលបានបំពេញរួចឡើង',
    'Review the preview before importing' => 'ពិនិត្យមើលជាមុនមុនពេលនាំចូល',

    // Error messages
    'No data to import' => 'គ្មានទិន្នន័យដើម្បីនាំចូល',
    'Invalid file format' => 'ទម្រង់ឯកសារមិនត្រឹមត្រូវ',
    'Missing required fields' => 'បាត់វាលដែលចាំបាច់',
    'Duplicate records found' => 'រកឃើញកំណត់ត្រាស្ទួន',
    'Import partially successful' => 'នាំចូលជោគជ័យមួយផ្នែក',
    'Some records were skipped' => 'កំណត់ត្រាមួយចំនួនត្រូវបានរំលង',
    'Check the error log for details' => 'ពិនិត្យកំណត់ហេតុកំហុសសម្រាប់ព័ត៌មានលម្អិត',

    // Report descriptions
    'Shows individual student progress over time' => 'បង្ហាញវឌ្ឍនភាពសិស្សម្នាក់ៗតាមពេលវេលា',
    'Compare performance across different schools' => 'ប្រៀបធៀបសមិទ្ធផលរវាងសាលាផ្សេងៗ',
    'Track overall progress from baseline to endline' => 'តាមដានវឌ្ឍនភាពរួមពីមូលដ្ឋានដល់ចុងក្រោយ',
    'Analyze the effectiveness of mentoring visits' => 'វិភាគប្រសិទ្ធភាពនៃការមកសួរសុខទុក្ខ',
    'View detailed assessment results' => 'មើលលទ្ធផលវាយតម្លៃលម្អិត',
    'Export data for further analysis' => 'នាំចេញទិន្នន័យសម្រាប់ការវិភាគបន្ថែម',
    'Generate custom reports' => 'បង្កើតរបាយការណ៍ផ្ទាល់ខ្លួន',
    'Schedule automatic reports' => 'កំណត់ពេលរបាយការណ៍ស្វ័យប្រវត្តិ',

    // School import specific
    'School Import' => 'នាំចូលសាលា',
    'Import Schools' => 'នាំចូលសាលា',
    'School Import Complete' => 'ការនាំចូលសាលាបានបញ្ចប់',
    'schools imported successfully' => 'សាលាបាននាំចូលដោយជោគជ័យ',
    'School data validation' => 'ការផ្ទៀងផ្ទាត់ទិន្នន័យសាលា',
    'School already exists' => 'សាលាមានរួចហើយ',
    'Invalid school code' => 'លេខកូដសាលាមិនត្រឹមត្រូវ',
    'Missing school name' => 'បាត់ឈ្មោះសាលា',

    // User import specific
    'User Import' => 'នាំចូលអ្នកប្រើប្រាស់',
    'Import Users' => 'នាំចូលអ្នកប្រើប្រាស់',
    'User Import Complete' => 'ការនាំចូលអ្នកប្រើប្រាស់បានបញ្ចប់',
    'users imported successfully' => 'អ្នកប្រើប្រាស់បាននាំចូលដោយជោគជ័យ',
    'User data validation' => 'ការផ្ទៀងផ្ទាត់ទិន្នន័យអ្នកប្រើប្រាស់',
    'Email already exists' => 'អ៊ីមែលមានរួចហើយ',
    'Invalid email format' => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវ',
    'Invalid role specified' => 'តួនាទីដែលបានបញ្ជាក់មិនត្រឹមត្រូវ',

    // Student import specific
    'Student Import' => 'នាំចូលសិស្ស',
    'Student Import Complete' => 'ការនាំចូលសិស្សបានបញ្ចប់',
    'students imported successfully' => 'សិស្សបាននាំចូលដោយជោគជ័យ',
    'Student data validation' => 'ការផ្ទៀងផ្ទាត់ទិន្នន័យសិស្ស',
    'Student already exists' => 'សិស្សមានរួចហើយ',
    'Invalid student code' => 'លេខកូដសិស្សមិនត្រឹមត្រូវ',
    'Invalid grade level' => 'កម្រិតថ្នាក់មិនត្រឹមត្រូវ',
    'Age out of range' => 'អាយុក្រៅពីកម្រិត',

    // Settings descriptions
    'Configure system-wide settings' => 'កំណត់រចនាសម្ព័ន្ធការកំណត់ទូទាំងប្រព័ន្ធ',
    'Manage application preferences' => 'គ្រប់គ្រងចំណូលចិត្តកម្មវិធី',
    'Set default values' => 'កំណត់តម្លៃលំនាំដើម',
    'Customize system behavior' => 'ប្តូរតាមបំណងឥរិយាបថប្រព័ន្ធ',
    'Configure email notifications' => 'កំណត់រចនាសម្ព័ន្ធការជូនដំណឹងអ៊ីមែល',
    'Set language preferences' => 'កំណត់ចំណូលចិត្តភាសា',
    'Manage security settings' => 'គ្រប់គ្រងការកំណត់សុវត្ថិភាព',
    'Configure backup settings' => 'កំណត់រចនាសម្ព័ន្ធការកំណត់បម្រុងទុក',

    // Dashboard widgets
    'Recent Activity' => 'សកម្មភាពថ្មីៗ',
    'System Overview' => 'ទិដ្ឋភាពទូទៅប្រព័ន្ធ',
    'Performance Metrics' => 'រង្វាស់សមិទ្ធផល',
    'Quick Stats' => 'ស្ថិតិរហ័ស',
    'Trending Data' => 'ទិន្នន័យនិន្នាការ',
    'Activity Feed' => 'មតិព័ត៌មានសកម្មភាព',
    'Notifications Center' => 'មជ្ឈមណ្ឌលការជូនដំណឹង',
    'Task List' => 'បញ្ជីកិច្ចការ',
    'Calendar View' => 'ទិដ្ឋភាពប្រតិទិន',
    'Recent Updates' => 'បច្ចុប្បន្នភាពថ្មីៗ',

    // File upload messages
    'Drop files here or click to upload' => 'ទម្លាក់ឯកសារនៅទីនេះ ឬចុចដើម្បីផ្ទុកឡើង',
    'Uploading...' => 'កំពុងផ្ទុកឡើង...',
    'Upload complete' => 'ការផ្ទុកឡើងបានបញ្ចប់',
    'Upload failed' => 'ការផ្ទុកឡើងបានបរាជ័យ',
    'File too large' => 'ឯកសារធំពេក',
    'Invalid file type' => 'ប្រភេទឯកសារមិនត្រឹមត្រូវ',
    'Maximum file size is' => 'ទំហំឯកសារអតិបរមាគឺ',
    'Allowed file types' => 'ប្រភេទឯកសារដែលអនុញ្ញាត',

    // Pagination
    'First Page' => 'ទំព័រដំបូង',
    'Previous Page' => 'ទំព័រមុន',
    'Next Page' => 'ទំព័របន្ទាប់',
    'Last Page' => 'ទំព័រចុងក្រោយ',
    'Go to page' => 'ទៅកាន់ទំព័រ',
    'Show' => 'បង្ហាញ',
    'entries' => 'ធាតុ',
    'Showing :start to :end of :total entries' => 'បង្ហាញ :start ដល់ :end នៃ :total ធាតុ',

    // Sorting
    'Sort by' => 'តម្រៀបតាម',
    'Sort ascending' => 'តម្រៀបឡើង',
    'Sort descending' => 'តម្រៀបចុះ',
    'Default sorting' => 'ការតម្រៀបលំនាំដើម',
    'Custom sorting' => 'ការតម្រៀបផ្ទាល់ខ្លួន',

    // Validation specific
    'This field is required' => 'វាលនេះចាំបាច់ត្រូវបំពេញ',
    'Please enter a valid email' => 'សូមបញ្ចូលអ៊ីមែលត្រឹមត្រូវ',
    'Please enter a valid number' => 'សូមបញ្ចូលលេខត្រឹមត្រូវ',
    'Please select an option' => 'សូមជ្រើសរើសជម្រើសមួយ',
    'Please check this box' => 'សូមគូសប្រអប់នេះ',
    'Please fill out this field' => 'សូមបំពេញវាលនេះ',
    'Value must be between' => 'តម្លៃត្រូវតែនៅចន្លោះ',
    'and' => 'និង',

    // Additional status
    'Ready' => 'រួចរាល់',
    'Not Ready' => 'មិនទាន់រួចរាល់',
    'Started' => 'បានចាប់ផ្តើម',
    'Not Started' => 'មិនទាន់ចាប់ផ្តើម',
    'In Queue' => 'នៅក្នុងជួរ',
    'Queued' => 'បានដាក់ជួរ',
    'Running' => 'កំពុងដំណើរការ',
    'Stopped' => 'បានឈប់',
    'Paused' => 'បានផ្អាក',
    'Resumed' => 'បានបន្ត',

    // Additional actions
    'Approve' => 'អនុម័ត',
    'Reject' => 'បដិសេធ',
    'Review' => 'ពិនិត្យ',
    'Archive' => 'រក្សាទុក',
    'Restore' => 'ស្តារឡើងវិញ',
    'Duplicate' => 'ចម្លង',
    'Move' => 'ផ្លាស់ទី',
    'Copy' => 'ចម្លង',
    'Rename' => 'ប្តូរឈ្មោះ',
    'Lock' => 'ចាក់សោ',
    'Unlock' => 'ដោះសោ',
    'Enable' => 'បើក',
    'Disable' => 'បិទ',
];

echo "Updating views translations batch 5...\n";
$count = 0;

foreach ($batch5Translations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->update(['km' => $km]);

    if ($updated > 0) {
        echo "  ✓ Updated: {$en}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} translations in batch 5.\n";

// Clear cache
Translation::clearCache();

// Check status
$remaining = Translation::where('group', 'views')
    ->where(function ($q) {
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
