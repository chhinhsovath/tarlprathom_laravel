<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Messages Group Translations
$messageTranslations = [
    // Success messages
    'Created successfully' => 'បានបង្កើតដោយជោគជ័យ',
    'Updated successfully' => 'បានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ',
    'Deleted successfully' => 'បានលុបដោយជោគជ័យ',
    'Saved successfully' => 'បានរក្សាទុកដោយជោគជ័យ',
    'Imported successfully' => 'បាននាំចូលដោយជោគជ័យ',
    'Exported successfully' => 'បាននាំចេញដោយជោគជ័យ',
    'Operation completed successfully' => 'ប្រតិបត្តិការបានបញ្ចប់ដោយជោគជ័យ',
    'Record has been created' => 'កំណត់ត្រាត្រូវបានបង្កើត',
    'Record has been updated' => 'កំណត់ត្រាត្រូវបានធ្វើបច្ចុប្បន្នភាព',
    'Record has been deleted' => 'កំណត់ត្រាត្រូវបានលុប',
    'File uploaded successfully' => 'ឯកសារត្រូវបានផ្ទុកឡើងដោយជោគជ័យ',
    'Data imported successfully' => 'ទិន្នន័យត្រូវបាននាំចូលដោយជោគជ័យ',
    'Data exported successfully' => 'ទិន្នន័យត្រូវបាននាំចេញដោយជោគជ័យ',
    'Settings saved successfully' => 'ការកំណត់ត្រូវបានរក្សាទុកដោយជោគជ័យ',
    'Profile updated successfully' => 'ប្រវត្តិរូបត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ',
    'Password changed successfully' => 'ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ',
    'Login successful' => 'ចូលដោយជោគជ័យ',
    'Logout successful' => 'ចេញដោយជោគជ័យ',
    'Registration successful' => 'ចុះឈ្មោះដោយជោគជ័យ',
    'Email sent successfully' => 'អ៊ីមែលត្រូវបានផ្ញើដោយជោគជ័យ',
    'Message sent successfully' => 'សារត្រូវបានផ្ញើដោយជោគជ័យ',
    'Verification successful' => 'ការផ្ទៀងផ្ទាត់ដោយជោគជ័យ',
    'Activation successful' => 'ការធ្វើឱ្យសកម្មដោយជោគជ័យ',
    'Deactivation successful' => 'ការធ្វើឱ្យអសកម្មដោយជោគជ័យ',
    'Submission successful' => 'ការដាក់ស្នើដោយជោគជ័យ',
    'Approval successful' => 'ការអនុម័តដោយជោគជ័យ',
    'Rejection successful' => 'ការបដិសេធដោយជោគជ័យ',
    'Upload complete' => 'ការផ្ទុកឡើងបានបញ្ចប់',
    'Download complete' => 'ការទាញយកបានបញ្ចប់',
    'Process completed' => 'ដំណើរការបានបញ្ចប់',
    'Task completed' => 'កិច្ចការបានបញ្ចប់',
    'Changes saved' => 'ការផ្លាស់ប្តូរត្រូវបានរក្សាទុក',
    'Settings applied' => 'ការកំណត់ត្រូវបានអនុវត្ត',
    'Configuration updated' => 'ការកំណត់រចនាសម្ព័ន្ធត្រូវបានធ្វើបច្ចុប្បន្នភាព',
    
    // Error messages
    'An error occurred' => 'មានកំហុសកើតឡើង',
    'Not found' => 'រកមិនឃើញ',
    'Unauthorized' => 'គ្មានសិទ្ធិ',
    'Invalid data' => 'ទិន្នន័យមិនត្រឹមត្រូវ',
    'File not found' => 'រកមិនឃើញឯកសារ',
    'Invalid file format' => 'ទម្រង់ឯកសារមិនត្រឹមត្រូវ',
    'An unexpected error occurred' => 'កំហុសដែលមិនបានរំពឹងទុកបានកើតឡើង',
    'Unable to process your request' => 'មិនអាចដំណើរការសំណើរបស់អ្នកបានទេ',
    'Invalid input data' => 'ទិន្នន័យបញ្ចូលមិនត្រឹមត្រូវ',
    'Access denied' => 'ការចូលប្រើត្រូវបានបដិសេធ',
    'Session expired, please login again' => 'វគ្គបានផុតកំណត់ សូមចូលម្តងទៀត',
    'Permission denied' => 'សិទ្ធិត្រូវបានបដិសេធ',
    'Invalid credentials' => 'ព័ត៌មានផ្ទៀងផ្ទាត់មិនត្រឹមត្រូវ',
    'Account not found' => 'រកមិនឃើញគណនី',
    'User not found' => 'រកមិនឃើញអ្នកប្រើប្រាស់',
    'Record not found' => 'រកមិនឃើញកំណត់ត្រា',
    'Page not found' => 'រកមិនឃើញទំព័រ',
    'Resource not found' => 'រកមិនឃើញធនធាន',
    'Server error' => 'កំហុសម៉ាស៊ីនមេ',
    'Network error' => 'កំហុសបណ្តាញ',
    'Connection error' => 'កំហុសការតភ្ជាប់',
    'Timeout error' => 'កំហុសអស់ពេល',
    'Database error' => 'កំហុសមូលដ្ឋានទិន្នន័យ',
    'Validation error' => 'កំហុសការផ្ទៀងផ្ទាត់',
    'Authentication failed' => 'ការផ្ទៀងផ្ទាត់បានបរាជ័យ',
    'Authorization failed' => 'ការអនុញ្ញាតបានបរាជ័យ',
    'Upload failed' => 'ការផ្ទុកឡើងបានបរាជ័យ',
    'Download failed' => 'ការទាញយកបានបរាជ័យ',
    'Import failed' => 'ការនាំចូលបានបរាជ័យ',
    'Export failed' => 'ការនាំចេញបានបរាជ័យ',
    'Operation failed' => 'ប្រតិបត្តិការបានបរាជ័យ',
    'Process failed' => 'ដំណើរការបានបរាជ័យ',
    'Invalid request' => 'សំណើមិនត្រឹមត្រូវ',
    'Bad request' => 'សំណើមិនល្អ',
    'Forbidden' => 'ហាមឃាត់',
    'Method not allowed' => 'វិធីសាស្ត្រមិនត្រូវបានអនុញ្ញាត',
    'Service unavailable' => 'សេវាមិនមាន',
    'Too many requests' => 'សំណើច្រើនពេក',
    
    // Info messages
    'Please select at least one item' => 'សូមជ្រើសរើសយ៉ាងហោចណាស់មួយ',
    'No changes were made' => 'គ្មានការផ្លាស់ប្តូរត្រូវបានធ្វើទេ',
    'Please fill in all required fields' => 'សូមបំពេញគ្រប់វាលដែលចាំបាច់',
    'Loading data, please wait' => 'កំពុងផ្ទុកទិន្នន័យ សូមរង់ចាំ',
    'Processing your request' => 'កំពុងដំណើរការសំណើរបស់អ្នក',
    'Please wait while we process your request' => 'សូមរង់ចាំខណៈពេលយើងដំណើរការសំណើរបស់អ្នក',
    'No data to display' => 'គ្មានទិន្នន័យដើម្បីបង្ហាញ',
    'No results match your search' => 'គ្មានលទ្ធផលត្រូវនឹងការស្វែងរករបស់អ្នក',
    'Showing results for' => 'បង្ហាញលទ្ធផលសម្រាប់',
    'Search results' => 'លទ្ធផលស្វែងរក',
    'Found results' => 'បានរកឃើញលទ្ធផល',
    'No matches found' => 'រកមិនឃើញការផ្គូផ្គង',
    'Please try again' => 'សូមព្យាយាមម្តងទៀត',
    'Please check your input' => 'សូមពិនិត្យការបញ្ចូលរបស់អ្នក',
    'Please correct the errors' => 'សូមកែកំហុស',
    'Please review your changes' => 'សូមពិនិត្យមើលការផ្លាស់ប្តូររបស់អ្នក',
    'Please confirm your selection' => 'សូមបញ្ជាក់ការជ្រើសរើសរបស់អ្នក',
    'Session will expire soon' => 'វគ្គនឹងផុតកំណត់ឆាប់ៗនេះ',
    'Your session has expired' => 'វគ្គរបស់អ្នកបានផុតកំណត់',
    'Please refresh the page' => 'សូមផ្ទុកទំព័រឡើងវិញ',
    'New version available' => 'មានកំណែថ្មី',
    'Update available' => 'មានការធ្វើបច្ចុប្បន្នភាព',
    'Maintenance mode' => 'របៀបថែទាំ',
    'System under maintenance' => 'ប្រព័ន្ធកំពុងថែទាំ',
    'Feature coming soon' => 'មុខងារនឹងមកដល់ឆាប់ៗ',
    'Not implemented yet' => 'មិនទាន់បានអនុវត្តនៅឡើយ',
    'Work in progress' => 'កំពុងដំណើរការ',
    'Beta version' => 'កំណែបេតា',
    'Preview mode' => 'របៀបមើលជាមុន',
    'Read only mode' => 'របៀបអានតែប៉ុណ្ណោះ',
    'Limited access' => 'ការចូលប្រើមានកម្រិត',
    'Full access' => 'ការចូលប្រើពេញលេញ',
    
    // Confirmation messages
    'Are you sure?' => 'តើអ្នកប្រាកដទេ?',
    'This action cannot be undone' => 'សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ',
    'Please confirm' => 'សូមបញ្ជាក់',
    'Are you sure you want to delete this record?' => 'តើអ្នកប្រាកដថាចង់លុបកំណត់ត្រានេះទេ?',
    'Are you sure you want to proceed?' => 'តើអ្នកប្រាកដថាចង់បន្តទេ?',
    'Are you sure you want to cancel?' => 'តើអ្នកប្រាកដថាចង់បោះបង់ទេ?',
    'Are you sure you want to save changes?' => 'តើអ្នកប្រាកដថាចង់រក្សាទុកការផ្លាស់ប្តូរទេ?',
    'Are you sure you want to logout?' => 'តើអ្នកប្រាកដថាចង់ចេញទេ?',
    'Are you sure you want to delete?' => 'តើអ្នកប្រាកដថាចង់លុបទេ?',
    'Are you sure you want to remove?' => 'តើអ្នកប្រាកដថាចង់ដកចេញទេ?',
    'Are you sure you want to reset?' => 'តើអ្នកប្រាកដថាចង់កំណត់ឡើងវិញទេ?',
    'Are you sure you want to clear?' => 'តើអ្នកប្រាកដថាចង់សម្អាតទេ?',
    'Do you want to continue?' => 'តើអ្នកចង់បន្តទេ?',
    'Do you want to save changes?' => 'តើអ្នកចង់រក្សាទុកការផ្លាស់ប្តូរទេ?',
    'Unsaved changes will be lost' => 'ការផ្លាស់ប្តូរដែលមិនបានរក្សាទុកនឹងត្រូវបាត់បង់',
    'All data will be permanently deleted' => 'ទិន្នន័យទាំងអស់នឹងត្រូវបានលុបជាអចិន្ត្រៃយ៍',
    'This will delete all related records' => 'នេះនឹងលុបកំណត់ត្រាដែលពាក់ព័ន្ធទាំងអស់',
    'You will not be able to recover this' => 'អ្នកនឹងមិនអាចស្តារវាឡើងវិញបានទេ',
    'Please type DELETE to confirm' => 'សូមវាយ DELETE ដើម្បីបញ្ជាក់',
    'Please enter your password to confirm' => 'សូមបញ្ចូលពាក្យសម្ងាត់របស់អ្នកដើម្បីបញ្ជាក់',
    
    // Warning messages
    'Warning' => 'ការព្រមាន',
    'Caution' => 'ប្រុងប្រយ័ត្ន',
    'Attention' => 'ចំណាប់អារម្មណ៍',
    'Important' => 'សំខាន់',
    'Notice' => 'សេចក្តីជូនដំណឹង',
    'Alert' => 'ការជូនដំណឹង',
    'Reminder' => 'ការរំលឹក',
    'Tip' => 'គន្លឹះ',
    'Hint' => 'ការណែនាំ',
    'Note' => 'ចំណាំ',
    'Information' => 'ព័ត៌មាន',
    'Help' => 'ជំនួយ',
    'Low disk space' => 'ទំហំថាសមានកម្រិតទាប',
    'Weak password' => 'ពាក្យសម្ងាត់ខ្សោយ',
    'Unsecured connection' => 'ការតភ្ជាប់គ្មានសុវត្ថិភាព',
    'Outdated browser' => 'កម្មវិធីរុករកហួសសម័យ',
    'Slow connection' => 'ការតភ្ជាប់យឺត',
    'Large file size' => 'ទំហំឯកសារធំ',
    'Limited storage' => 'ការផ្ទុកមានកម្រិត',
    'Quota exceeded' => 'កូតាបានលើស',
    'Trial period ending' => 'រយៈពេលសាកល្បងជិតផុត',
    'License expiring' => 'អាជ្ញាប័ណ្ណជិតផុតកំណត់',
    'Backup recommended' => 'ណែនាំឱ្យបម្រុងទុក',
    'Update required' => 'ត្រូវការការធ្វើបច្ចុប្បន្នភាព',
    'Action required' => 'ត្រូវការសកម្មភាព',
    'Review required' => 'ត្រូវការការពិនិត្យ',
    'Approval required' => 'ត្រូវការការអនុម័ត',
    'Confirmation required' => 'ត្រូវការការបញ្ជាក់',
    'Verification required' => 'ត្រូវការការផ្ទៀងផ្ទាត់',
];

echo "Updating message translations...\n";
$count = 0;

foreach ($messageTranslations as $en => $km) {
    Translation::where('key', $en)
        ->where('group', 'messages')
        ->update(['km' => $km]);
    $count++;
}

echo "Updated {$count} message translations.\n";

// Clear cache
Translation::clearCache();
echo "Cache cleared.\n";