<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;
use Illuminate\Support\Facades\File;

class TranslationCollector
{
    private $translations = [];

    private $bladePattern = '/(?:__|@lang|trans)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*(?:,\s*\[[^\]]*\])?\s*\)/';

    private $hardcodedPattern = '/>([^<>{}\n]+)</';

    public function collect()
    {
        echo "Starting translation collection...\n\n";

        // Scan Blade views
        $this->scanBladeViews();

        // Scan Models
        $this->scanModels();

        // Scan Controllers
        $this->scanControllers();

        // Add common UI elements
        $this->addCommonUIElements();

        // Add form labels
        $this->addFormLabels();

        // Add validation messages
        $this->addValidationMessages();

        // Add status messages
        $this->addStatusMessages();

        // Save to database
        $this->saveToDatabase();
    }

    private function scanBladeViews()
    {
        echo "Scanning Blade views...\n";
        $viewPath = resource_path('views');
        $files = File::allFiles($viewPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());

                // Find translation function calls
                preg_match_all($this->bladePattern, $content, $matches);
                foreach ($matches[1] as $key) {
                    $this->addTranslation($key, 'views');
                }

                // Find hardcoded text in HTML
                preg_match_all($this->hardcodedPattern, $content, $matches);
                foreach ($matches[1] as $text) {
                    $text = trim($text);
                    if ($this->isTranslatable($text)) {
                        $this->addTranslation($text, 'views');
                    }
                }
            }
        }
    }

    private function scanModels()
    {
        echo "Scanning Models...\n";

        // Model attributes that often need translation
        $modelTranslations = [
            // User model
            'Name' => 'ឈ្មោះ',
            'Email' => 'អ៊ីមែល',
            'Password' => 'ពាក្យសម្ងាត់',
            'Role' => 'តួនាទី',
            'Phone' => 'ទូរស័ព្ទ',
            'Sex' => 'ភេទ',
            'Male' => 'ប្រុស',
            'Female' => 'ស្រី',
            'Profile Photo' => 'រូបថតប្រវត្តិរូប',

            // School model
            'School Name' => 'ឈ្មោះសាលា',
            'School Code' => 'លេខកូដសាលា',
            'Province' => 'ខេត្ត',
            'District' => 'ស្រុក',
            'Commune' => 'ឃុំ',
            'Cluster' => 'ចង្កោម',

            // Student model
            'Student Name' => 'ឈ្មោះសិស្ស',
            'Student Code' => 'លេខកូដសិស្ស',
            'Date of Birth' => 'ថ្ងៃខែឆ្នាំកំណើត',
            'Age' => 'អាយុ',
            'Grade' => 'ថ្នាក់',
            'Student Photo' => 'រូបថតសិស្ស',

            // Assessment model
            'Assessment Date' => 'កាលបរិច្ឆេទវាយតម្លៃ',
            'Subject' => 'មុខវិជ្ជា',
            'Level' => 'កម្រិត',
            'Cycle' => 'វដ្ត',
            'Score' => 'ពិន្ទុ',

            // Mentoring model
            'Visit Date' => 'កាលបរិច្ឆេទទស្សនា',
            'Observation Notes' => 'កំណត់ចំណាំការសង្កេត',
            'Feedback' => 'មតិយោបល់',
            'Next Steps' => 'ជំហានបន្ទាប់',
        ];

        foreach ($modelTranslations as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'models',
            ];
        }
    }

    private function scanControllers()
    {
        echo "Scanning Controllers...\n";

        // Common controller messages
        $controllerMessages = [
            // Success messages
            'Created successfully' => 'បានបង្កើតដោយជោគជ័យ',
            'Updated successfully' => 'បានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ',
            'Deleted successfully' => 'បានលុបដោយជោគជ័យ',
            'Saved successfully' => 'បានរក្សាទុកដោយជោគជ័យ',
            'Imported successfully' => 'បាននាំចូលដោយជោគជ័យ',
            'Exported successfully' => 'បាននាំចេញដោយជោគជ័យ',

            // Error messages
            'An error occurred' => 'មានកំហុសកើតឡើង',
            'Not found' => 'រកមិនឃើញ',
            'Unauthorized' => 'គ្មានសិទ្ធិ',
            'Invalid data' => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            'File not found' => 'រកមិនឃើញឯកសារ',
            'Invalid file format' => 'ទម្រង់ឯកសារមិនត្រឹមត្រូវ',

            // Confirmation messages
            'Are you sure?' => 'តើអ្នកប្រាកដទេ?',
            'This action cannot be undone' => 'សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ',
            'Please confirm' => 'សូមបញ្ជាក់',
        ];

        foreach ($controllerMessages as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'messages',
            ];
        }
    }

    private function addCommonUIElements()
    {
        echo "Adding common UI elements...\n";

        $uiElements = [
            // Actions
            'Add' => 'បន្ថែម',
            'Edit' => 'កែសម្រួល',
            'Delete' => 'លុប',
            'Save' => 'រក្សាទុក',
            'Cancel' => 'បោះបង់',
            'Submit' => 'ដាក់ស្នើ',
            'Search' => 'ស្វែងរក',
            'Filter' => 'ចម្រាញ់',
            'Export' => 'នាំចេញ',
            'Import' => 'នាំចូល',
            'Download' => 'ទាញយក',
            'Upload' => 'ផ្ទុកឡើង',
            'Print' => 'បោះពុម្ព',
            'Reset' => 'កំណត់ឡើងវិញ',
            'Back' => 'ត្រឡប់',
            'Next' => 'បន្ទាប់',
            'Previous' => 'មុន',
            'Close' => 'បិទ',
            'View' => 'មើល',
            'Select' => 'ជ្រើសរើស',
            'Choose' => 'ជ្រើស',
            'Browse' => 'រុករក',
            'Clear' => 'សម្អាត',
            'Refresh' => 'ផ្ទុកឡើងវិញ',

            // Status
            'Active' => 'សកម្ម',
            'Inactive' => 'អសកម្ម',
            'Pending' => 'កំពុងរង់ចាំ',
            'Approved' => 'បានអនុម័ត',
            'Rejected' => 'បានបដិសេធ',
            'Completed' => 'បានបញ្ចប់',
            'In Progress' => 'កំពុងដំណើរការ',
            'Draft' => 'ព្រាង',
            'Published' => 'បានផ្សព្វផ្សាយ',
            'Archived' => 'បានរក្សាទុក',

            // Navigation
            'Dashboard' => 'ផ្ទាំងគ្រប់គ្រង',
            'Home' => 'ទំព័រដើម',
            'Profile' => 'ប្រវត្តិរូប',
            'Settings' => 'ការកំណត់',
            'Help' => 'ជំនួយ',
            'Logout' => 'ចេញ',
            'Login' => 'ចូល',
            'Register' => 'ចុះឈ្មោះ',

            // Table headers
            'No' => 'ល.រ',
            'Actions' => 'សកម្មភាព',
            'Created At' => 'បង្កើតនៅ',
            'Updated At' => 'ធ្វើបច្ចុប្បន្នភាពនៅ',
            'Status' => 'ស្ថានភាព',
            'Description' => 'ការពិពណ៌នា',

            // Pagination
            'Showing' => 'បង្ហាញ',
            'to' => 'ដល់',
            'of' => 'នៃ',
            'results' => 'លទ្ធផល',
            'First' => 'ដំបូង',
            'Last' => 'ចុងក្រោយ',
            'Per Page' => 'ក្នុងមួយទំព័រ',

            // Time
            'Today' => 'ថ្ងៃនេះ',
            'Yesterday' => 'ម្សិលមិញ',
            'This Week' => 'សប្តាហ៍នេះ',
            'Last Week' => 'សប្តាហ៍មុន',
            'This Month' => 'ខែនេះ',
            'Last Month' => 'ខែមុន',
            'This Year' => 'ឆ្នាំនេះ',
            'Last Year' => 'ឆ្នាំមុន',

            // Common phrases
            'All' => 'ទាំងអស់',
            'None' => 'គ្មាន',
            'Yes' => 'បាទ/ចាស',
            'No' => 'ទេ',
            'Or' => 'ឬ',
            'And' => 'និង',
            'From' => 'ពី',
            'To' => 'ទៅ',
            'By' => 'ដោយ',
            'For' => 'សម្រាប់',
            'With' => 'ជាមួយ',
            'Total' => 'សរុប',
            'Average' => 'មធ្យម',
            'Count' => 'ចំនួន',
            'Sum' => 'បូកសរុប',
            'Loading...' => 'កំពុងផ្ទុក...',
            'Please wait...' => 'សូមរង់ចាំ...',
            'No data available' => 'មិនមានទិន្នន័យ',
            'No records found' => 'រកមិនឃើញកំណត់ត្រា',
        ];

        foreach ($uiElements as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'ui',
            ];
        }
    }

    private function addFormLabels()
    {
        echo "Adding form labels...\n";

        $formLabels = [
            // User forms
            'Full Name' => 'ឈ្មោះពេញ',
            'First Name' => 'នាមខ្លួន',
            'Last Name' => 'នាមត្រកូល',
            'Email Address' => 'អាសយដ្ឋានអ៊ីមែល',
            'Phone Number' => 'លេខទូរស័ព្ទ',
            'Password' => 'ពាក្យសម្ងាត់',
            'Confirm Password' => 'បញ្ជាក់ពាក្យសម្ងាត់',
            'Current Password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្ន',
            'New Password' => 'ពាក្យសម្ងាត់ថ្មី',
            'Remember Me' => 'ចងចាំខ្ញុំ',
            'Select Role' => 'ជ្រើសរើសតួនាទី',
            'Select School' => 'ជ្រើសរើសសាលា',
            'Select Province' => 'ជ្រើសរើសខេត្ត',
            'Select District' => 'ជ្រើសរើសស្រុក',
            'Select Cluster' => 'ជ្រើសរើសចង្កោម',
            'Select Grade' => 'ជ្រើសរើសថ្នាក់',
            'Select Subject' => 'ជ្រើសរើសមុខវិជ្ជា',
            'Select Student' => 'ជ្រើសរើសសិស្ស',
            'Select Teacher' => 'ជ្រើសរើសគ្រូ',
            'Select Mentor' => 'ជ្រើសរើសអ្នកណែនាំ',
            'Select Date' => 'ជ្រើសរើសកាលបរិច្ឆេទ',
            'Start Date' => 'កាលបរិច្ឆេទចាប់ផ្តើម',
            'End Date' => 'កាលបរិច្ឆេទបញ្ចប់',
            'Choose File' => 'ជ្រើសរើសឯកសារ',
            'No file chosen' => 'មិនបានជ្រើសរើសឯកសារ',
            'Notes' => 'កំណត់ចំណាំ',
            'Comments' => 'មតិយោបល់',
            'Additional Information' => 'ព័ត៌មានបន្ថែម',
            'Required field' => 'ចាំបាច់បំពេញ',
            'Optional' => 'ស្រេចចិត្ត',
        ];

        foreach ($formLabels as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'forms',
            ];
        }
    }

    private function addValidationMessages()
    {
        echo "Adding validation messages...\n";

        $validationMessages = [
            'The :attribute field is required.' => 'វាល :attribute ត្រូវតែបំពេញ។',
            'The :attribute must be a valid email address.' => ':attribute ត្រូវតែជាអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវ។',
            'The :attribute must be at least :min characters.' => ':attribute ត្រូវតែមានយ៉ាងហោចណាស់ :min តួអក្សរ។',
            'The :attribute may not be greater than :max characters.' => ':attribute មិនត្រូវលើសពី :max តួអក្សរទេ។',
            'The :attribute confirmation does not match.' => 'ការបញ្ជាក់ :attribute មិនត្រូវគ្នាទេ។',
            'The :attribute has already been taken.' => ':attribute ត្រូវបានគេយករួចហើយ។',
            'The :attribute must be a number.' => ':attribute ត្រូវតែជាលេខ។',
            'The :attribute must be a file.' => ':attribute ត្រូវតែជាឯកសារ។',
            'The :attribute must be an image.' => ':attribute ត្រូវតែជារូបភាព។',
            'The :attribute must be a date.' => ':attribute ត្រូវតែជាកាលបរិច្ឆេទ។',
            'Please fix the errors below.' => 'សូមកែកំហុសខាងក្រោម។',
        ];

        foreach ($validationMessages as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'validation',
            ];
        }
    }

    private function addStatusMessages()
    {
        echo "Adding status messages...\n";

        $statusMessages = [
            // Success messages
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

            // Info messages
            'Please select at least one item' => 'សូមជ្រើសរើសយ៉ាងហោចណាស់មួយ',
            'No changes were made' => 'គ្មានការផ្លាស់ប្តូរត្រូវបានធ្វើទេ',
            'Please fill in all required fields' => 'សូមបំពេញគ្រប់វាលដែលចាំបាច់',
            'Loading data, please wait' => 'កំពុងផ្ទុកទិន្នន័យ សូមរង់ចាំ',

            // Warning messages
            'Are you sure you want to delete this record?' => 'តើអ្នកប្រាកដថាចង់លុបកំណត់ត្រានេះទេ?',
            'This action cannot be undone' => 'សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ',
            'Unsaved changes will be lost' => 'ការផ្លាស់ប្តូរដែលមិនបានរក្សាទុកនឹងត្រូវបាត់បង់',

            // Error messages
            'An unexpected error occurred' => 'កំហុសដែលមិនបានរំពឹងទុកបានកើតឡើង',
            'Unable to process your request' => 'មិនអាចដំណើរការសំណើរបស់អ្នកបានទេ',
            'Invalid input data' => 'ទិន្នន័យបញ្ចូលមិនត្រឹមត្រូវ',
            'Access denied' => 'ការចូលប្រើត្រូវបានបដិសេធ',
            'Session expired, please login again' => 'វគ្គបានផុតកំណត់ សូមចូលម្តងទៀត',
        ];

        foreach ($statusMessages as $en => $km) {
            $this->translations[] = [
                'key' => $en,
                'en' => $en,
                'km' => $km,
                'group' => 'messages',
            ];
        }
    }

    private function isTranslatable($text)
    {
        // Skip if too short or too long
        if (strlen($text) < 2 || strlen($text) > 100) {
            return false;
        }

        // Skip if only numbers or special characters
        if (! preg_match('/[a-zA-Z]/', $text)) {
            return false;
        }

        // Skip if it's a variable or code
        if (preg_match('/[\$\{\}]/', $text)) {
            return false;
        }

        return true;
    }

    private function addTranslation($key, $group)
    {
        // Skip if already added
        foreach ($this->translations as $trans) {
            if ($trans['key'] === $key) {
                return;
            }
        }

        $this->translations[] = [
            'key' => $key,
            'en' => $key,
            'km' => '', // Will be filled manually or via translation service
            'group' => $group,
        ];
    }

    private function saveToDatabase()
    {
        echo "\nSaving to database...\n";

        $count = 0;
        foreach ($this->translations as $trans) {
            Translation::updateOrCreate(
                ['key' => $trans['key']],
                [
                    'en' => $trans['en'],
                    'km' => $trans['km'] ?: $trans['en'], // Use English as fallback
                    'group' => $trans['group'],
                    'is_active' => true,
                ]
            );
            $count++;
        }

        echo "Total translations saved: {$count}\n";

        // Clear cache
        Translation::clearCache();
    }
}

// Run the collector
$collector = new TranslationCollector;
$collector->collect();
