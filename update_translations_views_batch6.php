<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Batch 6: Additional UI elements, table headers, and common phrases
$batch6Translations = [
    // Table headers and labels
    'ID' => 'លេខសម្គាល់',
    'Title' => 'ចំណងជើង',
    'Type' => 'ប្រភេទ',
    'Category' => 'ប្រភេទ',
    'Tags' => 'ស្លាក',
    'Priority' => 'អាទិភាព',
    'Assigned To' => 'ប្រគល់ទៅ',
    'Assigned By' => 'ប្រគល់ដោយ',
    'Created By' => 'បង្កើតដោយ',
    'Updated By' => 'ធ្វើបច្ចុប្បន្នភាពដោយ',
    'Deleted By' => 'លុបដោយ',
    'Approved By' => 'អនុម័តដោយ',
    'Reviewed By' => 'ពិនិត្យដោយ',
    'Submitted By' => 'ដាក់ស្នើដោយ',
    'Modified By' => 'កែប្រែដោយ',
    'Owner' => 'ម្ចាស់',
    'Manager' => 'អ្នកគ្រប់គ្រង',
    'Supervisor' => 'អ្នកត្រួតពិនិត្យ',
    'Department' => 'នាយកដ្ឋាន',
    'Location' => 'ទីតាំង',
    'Address' => 'អាសយដ្ឋាន',
    'Contact' => 'ទំនាក់ទំនង',
    'Phone Number' => 'លេខទូរស័ព្ទ',
    'Email Address' => 'អាសយដ្ឋានអ៊ីមែល',
    'Website' => 'គេហទំព័រ',
    'Social Media' => 'បណ្តាញសង្គម',
    
    // Date/Time labels
    'Start Date' => 'កាលបរិច្ឆេទចាប់ផ្តើម',
    'End Date' => 'កាលបរិច្ឆេទបញ្ចប់',
    'Due Date' => 'កាលបរិច្ឆេទកំណត់',
    'Created Date' => 'កាលបរិច្ឆេទបង្កើត',
    'Modified Date' => 'កាលបរិច្ឆេទកែប្រែ',
    'Published Date' => 'កាលបរិច្ឆេទផ្សព្វផ្សាយ',
    'Archived Date' => 'កាលបរិច្ឆេទរក្សាទុក',
    'Start Time' => 'ពេលវេលាចាប់ផ្តើម',
    'End Time' => 'ពេលវេលាបញ្ចប់',
    'Duration' => 'រយៈពេល',
    'Time Zone' => 'ល្វែងម៉ោង',
    'Schedule' => 'កាលវិភាគ',
    'Timeline' => 'បន្ទាត់ពេលវេលា',
    
    // Common actions and buttons
    'Apply' => 'អនុវត្ត',
    'Apply Changes' => 'អនុវត្តការផ្លាស់ប្តូរ',
    'Discard' => 'បោះបង់',
    'Discard Changes' => 'បោះបង់ការផ្លាស់ប្តូរ',
    'Proceed' => 'បន្ត',
    'Proceed with Caution' => 'បន្តដោយប្រុងប្រយ័ត្ន',
    'Abort' => 'បោះបង់',
    'Retry' => 'ព្យាយាមម្តងទៀត',
    'Skip' => 'រំលង',
    'Skip This Step' => 'រំលងជំហាននេះ',
    'Go Back' => 'ត្រឡប់ក្រោយ',
    'Go Forward' => 'ទៅមុខ',
    'Go Home' => 'ទៅទំព័រដើម',
    'Sign In' => 'ចូលប្រើ',
    'Sign Out' => 'ចាកចេញ',
    'Sign Up' => 'ចុះឈ្មោះ',
    'Subscribe' => 'ជាវ',
    'Unsubscribe' => 'ឈប់ជាវ',
    'Follow' => 'តាមដាន',
    'Unfollow' => 'ឈប់តាមដាន',
    'Like' => 'ចូលចិត្ត',
    'Unlike' => 'មិនចូលចិត្ត',
    'Share' => 'ចែករំលែក',
    'Comment' => 'មតិយោបល់',
    'Reply' => 'ឆ្លើយតប',
    'Forward' => 'បញ្ជូនបន្ត',
    'Mark as Read' => 'សម្គាល់ថាបានអាន',
    'Mark as Unread' => 'សម្គាល់ថាមិនទាន់អាន',
    'Mark as Important' => 'សម្គាល់ថាសំខាន់',
    'Flag' => 'ដាក់ទង់',
    'Unflag' => 'ដកទង់',
    'Pin' => 'ខ្ទាស់',
    'Unpin' => 'ដកខ្ទាស់',
    'Bookmark' => 'ចំណាំ',
    'Remove Bookmark' => 'ដកចំណាំ',
    
    // Counts and statistics
    'Total Count' => 'ចំនួនសរុប',
    'Active Count' => 'ចំនួនសកម្ម',
    'Inactive Count' => 'ចំនួនអសកម្ម',
    'Pending Count' => 'ចំនួនរង់ចាំ',
    'Completed Count' => 'ចំនួនបានបញ្ចប់',
    'Success Rate' => 'អត្រាជោគជ័យ',
    'Failure Rate' => 'អត្រាបរាជ័យ',
    'Completion Rate' => 'អត្រាបញ្ចប់',
    'Progress Rate' => 'អត្រាវឌ្ឍនភាព',
    'Growth Rate' => 'អត្រាកំណើន',
    'Average Score' => 'ពិន្ទុមធ្យម',
    'Highest Score' => 'ពិន្ទុខ្ពស់បំផុត',
    'Lowest Score' => 'ពិន្ទុទាបបំផុត',
    'Pass Rate' => 'អត្រាជាប់',
    'Fail Rate' => 'អត្រាធ្លាក់',
    
    // Messages and notifications
    'New Message' => 'សារថ្មី',
    'Unread Messages' => 'សារមិនទាន់អាន',
    'Read Messages' => 'សារបានអាន',
    'Sent Messages' => 'សារបានផ្ញើ',
    'Draft Messages' => 'សារព្រាង',
    'Deleted Messages' => 'សារបានលុប',
    'Message Center' => 'មជ្ឈមណ្ឌលសារ',
    'Notification Center' => 'មជ្ឈមណ្ឌលការជូនដំណឹង',
    'Alert Center' => 'មជ្ឈមណ្ឌលការជូនដំណឹង',
    'New Notification' => 'ការជូនដំណឹងថ្មី',
    'Mark All as Read' => 'សម្គាល់ទាំងអស់ថាបានអាន',
    'Clear All' => 'សម្អាតទាំងអស់',
    'View All Notifications' => 'មើលការជូនដំណឹងទាំងអស់',
    
    // Forms and fields
    'Form' => 'ទម្រង់',
    'Field' => 'វាល',
    'Required Field' => 'វាលចាំបាច់',
    'Optional Field' => 'វាលស្រេចចិត្ត',
    'Text Field' => 'វាលអត្ថបទ',
    'Number Field' => 'វាលលេខ',
    'Date Field' => 'វាលកាលបរិច្ឆេទ',
    'Time Field' => 'វាលពេលវេលា',
    'Email Field' => 'វាលអ៊ីមែល',
    'Password Field' => 'វាលពាក្យសម្ងាត់',
    'Dropdown Field' => 'វាលទម្លាក់ចុះ',
    'Checkbox Field' => 'វាលប្រអប់ធីក',
    'Radio Field' => 'វាលរ៉ាឌីយ៉ូ',
    'File Field' => 'វាលឯកសារ',
    'Textarea Field' => 'វាលតំបន់អត្ថបទ',
    'Submit Button' => 'ប៊ូតុងដាក់ស្នើ',
    'Reset Button' => 'ប៊ូតុងកំណត់ឡើងវិញ',
    'Cancel Button' => 'ប៊ូតុងបោះបង់',
    
    // System messages
    'System Message' => 'សារប្រព័ន្ធ',
    'System Alert' => 'ការជូនដំណឹងប្រព័ន្ធ',
    'System Error' => 'កំហុសប្រព័ន្ធ',
    'System Warning' => 'ការព្រមានប្រព័ន្ធ',
    'System Info' => 'ព័ត៌មានប្រព័ន្ធ',
    'System Status' => 'ស្ថានភាពប្រព័ន្ធ',
    'System Update' => 'បច្ចុប្បន្នភាពប្រព័ន្ធ',
    'System Maintenance' => 'ការថែទាំប្រព័ន្ធ',
    'System Backup' => 'ការបម្រុងទុកប្រព័ន្ធ',
    'System Restore' => 'ការស្តារប្រព័ន្ធ',
    
    // Permissions and access
    'Permission' => 'សិទ្ធិ',
    'Permissions' => 'សិទ្ធិ',
    'Access Level' => 'កម្រិតចូលប្រើ',
    'Access Control' => 'ការគ្រប់គ្រងការចូលប្រើ',
    'Grant Access' => 'ផ្តល់សិទ្ធិចូលប្រើ',
    'Deny Access' => 'បដិសេធការចូលប្រើ',
    'Revoke Access' => 'ដកហូតការចូលប្រើ',
    'Request Access' => 'ស្នើសុំការចូលប្រើ',
    'Access Granted' => 'បានផ្តល់សិទ្ធិចូលប្រើ',
    'Access Denied' => 'បានបដិសេធការចូលប្រើ',
    'Access Pending' => 'ការចូលប្រើកំពុងរង់ចាំ',
    'Full Access' => 'ការចូលប្រើពេញលេញ',
    'Limited Access' => 'ការចូលប្រើមានកម្រិត',
    'No Access' => 'គ្មានការចូលប្រើ',
    
    // Filters and sorting
    'Filter' => 'តម្រង',
    'Filters' => 'តម្រង',
    'Add Filter' => 'បន្ថែមតម្រង',
    'Remove Filter' => 'ដកតម្រង',
    'Filter By' => 'តម្រងតាម',
    'Sort' => 'តម្រៀប',
    'Sort By' => 'តម្រៀបតាម',
    'Order By' => 'តម្រៀបតាម',
    'Group By' => 'ដាក់ជាក្រុមតាម',
    'Ascending Order' => 'លំដាប់ឡើង',
    'Descending Order' => 'លំដាប់ចុះ',
    'Alphabetical Order' => 'លំដាប់អក្ខរក្រម',
    'Chronological Order' => 'លំដាប់កាលប្បវត្តិ',
    'Random Order' => 'លំដាប់ចៃដន្យ',
    
    // Additional common phrases
    'Please Wait' => 'សូមរង់ចាំ',
    'Please Try Again' => 'សូមព្យាយាមម្តងទៀត',
    'Please Select' => 'សូមជ្រើសរើស',
    'Please Enter' => 'សូមបញ្ចូល',
    'Please Confirm' => 'សូមបញ្ជាក់',
    'Please Review' => 'សូមពិនិត្យ',
    'Please Check' => 'សូមពិនិត្យ',
    'Please Note' => 'សូមកត់សម្គាល់',
    'Thank You' => 'អរគុណ',
    'Welcome Back' => 'សូមស្វាគមន៍ការត្រឡប់មកវិញ',
    'Good Job' => 'ល្អណាស់',
    'Well Done' => 'ធ្វើបានល្អ',
    'Congratulations' => 'សូមអបអរសាទរ',
    'Sorry' => 'សូមទោស',
    'Try Again' => 'ព្យាយាមម្តងទៀត',
    'Coming Soon' => 'មកដល់ឆាប់ៗ',
    'Under Construction' => 'កំពុងសាងសង់',
    'Under Development' => 'កំពុងអភិវឌ្ឍ',
    'Beta Version' => 'កំណែបេតា',
    'Alpha Version' => 'កំណែអាល់ហ្វា',
    'Test Mode' => 'របៀបសាកល្បង',
    'Debug Mode' => 'របៀបកែកំហុស',
    'Production Mode' => 'របៀបផលិតកម្ម',
    'Development Mode' => 'របៀបអភិវឌ្ឍន៍',
];

echo "Updating views translations batch 6...\n";
$count = 0;

foreach ($batch6Translations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->update(['km' => $km]);
    
    if ($updated > 0) {
        echo "  ✓ Updated: {$en}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} translations in batch 6.\n";

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