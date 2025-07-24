<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Batch 4: Additional UI elements, headers, and navigation items
$batch4Translations = [
    // User creation and management
    'Create User' => 'បង្កើតអ្នកប្រើប្រាស់',
    'Edit User' => 'កែសម្រួលអ្នកប្រើប្រាស់', 
    'User Details' => 'ព័ត៌មានលម្អិតអ្នកប្រើប្រាស់',
    'User Information' => 'ព័ត៌មានអ្នកប្រើប្រាស់',
    'User Profile' => 'ប្រវត្តិរូបអ្នកប្រើប្រាស់',
    'User Settings' => 'ការកំណត់អ្នកប្រើប្រាស់',
    'User Activity' => 'សកម្មភាពអ្នកប្រើប្រាស់',
    'User Roles' => 'តួនាទីអ្នកប្រើប្រាស់',
    'User Permissions' => 'សិទ្ធិអ្នកប្រើប្រាស់',
    'Account Information' => 'ព័ត៌មានគណនី',
    'Account Status' => 'ស្ថានភាពគណនី',
    'Create New User' => 'បង្កើតអ្នកប្រើប្រាស់ថ្មី',
    'Add User' => 'បន្ថែមអ្នកប្រើប្រាស់',
    'Holding Classes' => 'កាន់ថ្នាក់',
    'Assign School' => 'ចាត់តាំងសាលា',
    'Assign Role' => 'ចាត់តាំងតួនាទី',
    
    // Student management
    'Create Student' => 'បង្កើតសិស្ស',
    'Edit Student' => 'កែសម្រួលសិស្ស',
    'Student Details' => 'ព័ត៌មានលម្អិតសិស្ស',
    'Student Registration' => 'ការចុះឈ្មោះសិស្ស',
    'Student Profile' => 'ប្រវត្តិរូបសិស្ស',
    'Student Assessment' => 'ការវាយតម្លៃសិស្ស',
    'Student Progress' => 'វឌ្ឍនភាពសិស្ស',
    'Student History' => 'ប្រវត្តិសិស្ស',
    'Assessment History' => 'ប្រវត្តិការវាយតម្លៃ',
    'Add New Student' => 'បន្ថែមសិស្សថ្មី',
    'Student Management' => 'ការគ្រប់គ្រងសិស្ស',
    'Update Student' => 'ធ្វើបច្ចុប្បន្នភាពសិស្ស',
    'Remove Student' => 'ដកសិស្សចេញ',
    'Transfer Student' => 'ផ្ទេរសិស្ស',
    'Graduate Student' => 'សិស្សបញ្ចប់',
    
    // Reports and analysis
    'Generate Report' => 'បង្កើតរបាយការណ៍',
    'Export Report' => 'នាំចេញរបាយការណ៍',
    'Print Report' => 'បោះពុម្ពរបាយការណ៍',
    'Share Report' => 'ចែករំលែករបាយការណ៍',
    'Report Type' => 'ប្រភេទរបាយការណ៍',
    'Report Period' => 'រយៈពេលរបាយការណ៍',
    'Report Format' => 'ទម្រង់របាយការណ៍',
    'Custom Report' => 'របាយការណ៍ផ្ទាល់ខ្លួន',
    'Standard Report' => 'របាយការណ៍ស្តង់ដារ',
    'Detailed Report' => 'របាយការណ៍លម្អិត',
    'Summary Report' => 'របាយការណ៍សង្ខេប',
    'View All Reports' => 'មើលរបាយការណ៍ទាំងអស់',
    'Recent Reports' => 'របាយការណ៍ថ្មីៗ',
    
    // Assessment management
    'Create Assessment' => 'បង្កើតការវាយតម្លៃ',
    'Edit Assessment' => 'កែសម្រួលការវាយតម្លៃ',
    'Delete Assessment' => 'លុបការវាយតម្លៃ',
    'View Assessment' => 'មើលការវាយតម្លៃ',
    'Assessment Details' => 'ព័ត៌មានលម្អិតការវាយតម្លៃ',
    'Assessment Progress' => 'វឌ្ឍនភាពការវាយតម្លៃ',
    'Assessment Report' => 'របាយការណ៍វាយតម្លៃ',
    'Complete Assessment' => 'បញ្ចប់ការវាយតម្លៃ',
    'Start Assessment' => 'ចាប់ផ្តើមការវាយតម្លៃ',
    'Continue Assessment' => 'បន្តការវាយតម្លៃ',
    'Assessment Summary' => 'សង្ខេបការវាយតម្លៃ',
    'Assessment Status' => 'ស្ថានភាពការវាយតម្លៃ',
    'Assessment Settings' => 'ការកំណត់ការវាយតម្លៃ',
    
    // Date and time related
    'Date Created' => 'កាលបរិច្ឆេទបង្កើត',
    'Date Modified' => 'កាលបរិច្ឆេទកែប្រែ',
    'Date Completed' => 'កាលបរិច្ឆេទបញ្ចប់',
    'Date Started' => 'កាលបរិច្ឆេទចាប់ផ្តើម',
    'Date Range' => 'រយៈពេល',
    'Select Date Range' => 'ជ្រើសរើសរយៈពេល',
    'From' => 'ពី',
    'Until' => 'ដល់',
    'Between' => 'រវាង',
    'Duration' => 'រយៈពេល',
    'Time Remaining' => 'ពេលវេលានៅសល់',
    'Time Spent' => 'ពេលវេលាបានចំណាយ',
    
    // Import/Export operations
    'Import' => 'នាំចូល',
    'Export' => 'នាំចេញ',
    'Import Data' => 'នាំចូលទិន្នន័យ',
    'Export Data' => 'នាំចេញទិន្នន័យ',
    'Import File' => 'នាំចូលឯកសារ',
    'Export File' => 'នាំចេញឯកសារ',
    'Import Settings' => 'ការកំណត់នាំចូល',
    'Export Settings' => 'ការកំណត់នាំចេញ',
    'Import History' => 'ប្រវត្តិនាំចូល',
    'Export History' => 'ប្រវត្តិនាំចេញ',
    'Import Progress' => 'វឌ្ឍនភាពនាំចូល',
    'Export Progress' => 'វឌ្ឍនភាពនាំចេញ',
    'Import Successful' => 'នាំចូលដោយជោគជ័យ',
    'Export Successful' => 'នាំចេញដោយជោគជ័យ',
    'Import Failed' => 'នាំចូលបរាជ័យ',
    'Export Failed' => 'នាំចេញបរាជ័យ',
    
    // Filter and search
    'Apply Filters' => 'អនុវត្តតម្រង',
    'Reset Filters' => 'កំណត់តម្រងឡើងវិញ',
    'Advanced Search' => 'ការស្វែងរកកម្រិតខ្ពស់',
    'Quick Search' => 'ការស្វែងរករហ័ស',
    'Search Results' => 'លទ្ធផលស្វែងរក',
    'Search Criteria' => 'លក្ខណៈវិនិច្ឆ័យស្វែងរក',
    'Search Options' => 'ជម្រើសស្វែងរក',
    'No search results' => 'គ្មានលទ្ធផលស្វែងរក',
    'Found :count results' => 'រកឃើញ :count លទ្ធផល',
    'Searching...' => 'កំពុងស្វែងរក...',
    
    // Actions and operations
    'Bulk Actions' => 'សកម្មភាពច្រើន',
    'Quick Actions' => 'សកម្មភាពរហ័ស',
    'Recent Actions' => 'សកម្មភាពថ្មីៗ',
    'Action Required' => 'ត្រូវការសកម្មភាព',
    'Take Action' => 'ចាត់វិធានការ',
    'Action Log' => 'កំណត់ហេតុសកម្មភាព',
    'Action History' => 'ប្រវត្តិសកម្មភាព',
    'Undo Action' => 'មិនធ្វើសកម្មភាពវិញ',
    'Redo Action' => 'ធ្វើសកម្មភាពឡើងវិញ',
    'Confirm Action' => 'បញ្ជាក់សកម្មភាព',
    
    // School management
    'School Information' => 'ព័ត៌មានសាលា',
    'School Profile' => 'ប្រវត្តិសាលា',
    'School Statistics' => 'ស្ថិតិសាលា',
    'School Reports' => 'របាយការណ៍សាលា',
    'School Settings' => 'ការកំណត់សាលា',
    'Update School' => 'ធ្វើបច្ចុប្បន្នភាពសាលា',
    'Add School' => 'បន្ថែមសាលា',
    'Remove School' => 'ដកសាលាចេញ',
    'Transfer School' => 'ផ្ទេរសាលា',
    'School List' => 'បញ្ជីសាលា',
    'All Genders' => 'ភេទទាំងអស់',
    
    // Mentoring specific additional
    'Mentoring Session' => 'វគ្គការណែនាំ',
    'Mentoring Plan' => 'ផែនការណែនាំ',
    'Mentoring Schedule' => 'កាលវិភាគការណែនាំ',
    'Mentoring Report' => 'របាយការណ៍ការណែនាំ',
    'Mentoring Progress' => 'វឌ្ឍនភាពការណែនាំ',
    'Mentoring History' => 'ប្រវត្តិការណែនាំ',
    'Add Mentoring Visit' => 'បន្ថែមការមកសួរសុខទុក្ខ',
    
    // File operations
    'Upload' => 'ផ្ទុកឡើង',
    'Download' => 'ទាញយក',
    'Delete File' => 'លុបឯកសារ',
    'View File' => 'មើលឯកសារ',
    'Edit File' => 'កែសម្រួលឯកសារ',
    'Share File' => 'ចែករំលែកឯកសារ',
    'File Details' => 'ព័ត៌មានលម្អិតឯកសារ',
    'File Type' => 'ប្រភេទឯកសារ',
    'File Size' => 'ទំហំឯកសារ',
    'File Name' => 'ឈ្មោះឯកសារ',
    'Files' => 'ឯកសារ',
    
    // Settings categories
    'General Settings' => 'ការកំណត់ទូទៅ',
    'Privacy Settings' => 'ការកំណត់ឯកជនភាព',
    'Notification Settings' => 'ការកំណត់ការជូនដំណឹង',
    'Display Settings' => 'ការកំណត់ការបង្ហាញ',
    'Language Settings' => 'ការកំណត់ភាសា',
    'Advanced Settings' => 'ការកំណត់កម្រិតខ្ពស់',
    'System Preferences' => 'ចំណូលចិត្តប្រព័ន្ធ',
    
    // Navigation and menus
    'Main Navigation' => 'ការរុករកមេ',
    'Quick Links' => 'តំណរហ័ស',
    'Shortcuts' => 'ផ្លូវកាត់',
    'Favorites' => 'ចំណូលចិត្ត',
    'Recent Items' => 'ធាតុថ្មីៗ',
    'Popular Items' => 'ធាតុពេញនិយម',
    'Featured Items' => 'ធាតុពិសេស',
    
    // Status indicators additional
    'In Review' => 'កំពុងពិនិត្យ',
    'Under Review' => 'ក្រោមការពិនិត្យ',
    'Reviewed' => 'បានពិនិត្យ',
    'Verified' => 'បានផ្ទៀងផ្ទាត់',
    'Unverified' => 'មិនបានផ្ទៀងផ្ទាត់',
    'Confirmed' => 'បានបញ្ជាក់',
    'Unconfirmed' => 'មិនបានបញ្ជាក់',
    'Processing' => 'កំពុងដំណើរការ',
    'Processed' => 'បានដំណើរការ',
    'Scheduled' => 'បានកំណត់ពេល',
    'Cancelled' => 'បានលុបចោល',
    'Expired' => 'ផុតកំណត់',
    
    // Additional UI elements
    'Toggle' => 'បិទបើក',
    'Expand All' => 'ពង្រីកទាំងអស់',
    'Collapse All' => 'បង្រួមទាំងអស់',
    'Show Details' => 'បង្ហាញព័ត៌មានលម្អិត',
    'Hide Details' => 'លាក់ព័ត៌មានលម្អិត',
    'More Options' => 'ជម្រើសច្រើនទៀត',
    'Less Options' => 'ជម្រើសតិច',
    'Advanced Options' => 'ជម្រើសកម្រិតខ្ពស់',
    'Basic Options' => 'ជម្រើសមូលដ្ឋាន',
    'View Options' => 'ជម្រើសមើល',
    'Display Options' => 'ជម្រើសបង្ហាញ',
    'Format Options' => 'ជម្រើសទម្រង់',
    
    // Help and documentation
    'User Manual' => 'សៀវភៅណែនាំអ្នកប្រើ',
    'Quick Start Guide' => 'មគ្គុទ្ទេសក៍ចាប់ផ្តើមរហ័ស',
    'Video Tutorials' => 'មេរៀនវីដេអូ',
    'Contact Support' => 'ទាក់ទងផ្នែកគាំទ្រ',
    'Report Issue' => 'រាយការណ៍បញ្ហា',
    'Request Feature' => 'ស្នើសុំមុខងារ',
    'Give Feedback' => 'ផ្តល់មតិយោបល់',
    'Rate Us' => 'វាយតម្លៃយើង',
];

echo "Updating views translations batch 4...\n";
$count = 0;

foreach ($batch4Translations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->update(['km' => $km]);
    
    if ($updated > 0) {
        echo "  ✓ Updated: {$en}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} translations in batch 4.\n";

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