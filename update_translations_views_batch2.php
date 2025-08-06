<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Additional views translations for specific TaRL terms and phrases
$specificTranslations = [
    // TaRL specific terms
    'TaRL Project' => 'គម្រោង TaRL',
    'TaRL Assessment System' => 'ប្រព័ន្ធវាយតម្លៃ TaRL',
    'Teaching at the Right Level' => 'ការបង្រៀនតាមកម្រិតសមស្រប',
    'TaRL' => 'TaRL',

    // Assessment cycles
    'Baseline' => 'មូលដ្ឋាន',
    'Midline' => 'ពាក់កណ្តាល',
    'Endline' => 'ចុងក្រោយ',
    'Assessment Cycle' => 'វដ្តវាយតម្លៃ',
    'All Cycles' => 'វដ្តទាំងអស់',
    'Test Cycle' => 'វដ្តតេស្ត',

    // Subjects
    'Khmer' => 'ភាសាខ្មែរ',
    'Math' => 'គណិតវិទ្យា',
    'Language' => 'ភាសា',
    'Numeracy' => 'គណិតវិទ្យា',
    'All Subjects' => 'មុខវិជ្ជាទាំងអស់',

    // Reading levels
    'Beginner' => 'ចាប់ផ្តើម',
    'Letter' => 'អក្សរ',
    'Word' => 'ពាក្យ',
    'Sentence' => 'ប្រយោគ',
    'Paragraph' => 'កថាខណ្ឌ',
    'Story' => 'រឿង',
    'Reader' => 'អ្នកអាន',
    'Reading Level' => 'កម្រិតអាន',
    'Comp. 1' => 'សមាសភាគ ១',
    'Comp. 2' => 'សមាសភាគ ២',

    // Math levels
    '1-Digit' => 'លេខ១ខ្ទង់',
    '2-Digit' => 'លេខ២ខ្ទង់',
    'Addition' => 'ការបូក',
    'Subtraction' => 'ការដក',
    'Multiplication' => 'ការគុណ',
    'Division' => 'ការចែក',
    'Word Problem' => 'ល្បាកលេខ',
    'Basic Operations' => 'ប្រតិបត្តិការមូលដ្ឋាន',
    'Multiplication/Division' => 'ការគុណ/ការចែក',

    // Assessment related
    'Assessment Results' => 'លទ្ធផលវាយតម្លៃ',
    'Assessment Level' => 'កម្រិតវាយតម្លៃ',
    'Assessment Dates' => 'កាលបរិច្ឆេទវាយតម្លៃ',
    'Baseline Assessment' => 'ការវាយតម្លៃមូលដ្ឋាន',
    'Baseline Assessment Levels' => 'កម្រិតវាយតម្លៃមូលដ្ឋាន',
    'New Assessment' => 'ការវាយតម្លៃថ្មី',
    'Select Students' => 'ជ្រើសរើសសិស្ស',
    'Select Students for :type Assessment' => 'ជ្រើសរើសសិស្សម្រាប់ការវាយតម្លៃ :type',
    'Assessed' => 'បានវាយតម្លៃ',
    'Not Assessed' => 'មិនបានវាយតម្លៃ',
    'Assessment updated' => 'ការវាយតម្លៃបានធ្វើបច្ចុប្បន្នភាព',
    'Assessment saved' => 'ការវាយតម្លៃបានរក្សាទុក',
    'Failed to save assessment' => 'បរាជ័យក្នុងការរក្សាទុកការវាយតម្លៃ',
    'Submit All Assessments?' => 'ដាក់ស្នើការវាយតម្លៃទាំងអស់?',
    'Submitting assessments...' => 'កំពុងដាក់ស្នើការវាយតម្លៃ...',
    'Failed to submit assessments' => 'បរាជ័យក្នុងការដាក់ស្នើការវាយតម្លៃ',
    'Lock/unlock assessments and manage data' => 'ចាក់សោ/ដោះសោការវាយតម្លៃនិងគ្រប់គ្រងទិន្នន័យ',
    'Manage Assessments' => 'គ្រប់គ្រងការវាយតម្លៃ',
    'No assessments found' => 'រកមិនឃើញការវាយតម្លៃ',
    'No assessments have been saved yet' => 'មិនទាន់មានការវាយតម្លៃត្រូវបានរក្សាទុកនៅឡើយទេ',
    'You can only view these assessments.' => 'អ្នកអាចមើលតែការវាយតម្លៃទាំងនេះប៉ុណ្ណោះ។',

    // Student related
    'Total Students' => 'សិស្សសរុប',
    'Total Students Assessed' => 'សិស្សដែលបានវាយតម្លៃសរុប',
    'Number of Students' => 'ចំនួនសិស្ស',
    'Student Gender' => 'ភេទសិស្ស',
    'Student Level' => 'កម្រិតសិស្ស',
    'Student Information' => 'ព័ត៌មានសិស្ស',
    'students assessed' => 'សិស្សបានវាយតម្លៃ',
    'No students found.' => 'រកមិនឃើញសិស្ស។',
    'No eligible students' => 'គ្មានសិស្សមានសិទ្ធិ',
    'Import Students' => 'នាំចូលសិស្ស',
    'Add Student' => 'បន្ថែមសិស្ស',
    'Bulk import student data from spreadsheets' => 'នាំចូលទិន្នន័យសិស្សច្រើនពីតារាងគណនា',
    'All students are automatically eligible for baseline assessments.' => 'សិស្សទាំងអស់មានសិទ្ធិដោយស្វ័យប្រវត្តិសម្រាប់ការវាយតម្លៃមូលដ្ឋាន។',

    // School related
    'Manage Schools' => 'គ្រប់គ្រងសាលារៀន',
    'Schools Added' => 'សាលាបានបន្ថែម',
    'All Schools' => 'សាលាទាំងអស់',
    'Schools Template' => 'គំរូសាលា',
    'Total registered' => 'ចុះឈ្មោះសរុប',
    'Configure assessment dates for schools' => 'កំណត់រចនាសម្ព័ន្ធកាលបរិច្ឆេទវាយតម្លៃសម្រាប់សាលា',

    // User roles
    'Mentor' => 'អ្នកណែនាំ',
    'Mentors' => 'អ្នកណែនាំ',
    'All Mentors' => 'អ្នកណែនាំទាំងអស់',
    'Teachers' => 'គ្រូ',
    'All Teachers' => 'គ្រូទាំងអស់',
    'Coordinators' => 'អ្នកសម្របសម្រួល',
    'COORDINATOR' => 'អ្នកសម្របសម្រួល',
    'Active accounts' => 'គណនីសកម្ម',
    'Manage Users' => 'គ្រប់គ្រងអ្នកប្រើប្រាស់',
    'Import Users' => 'នាំចូលអ្នកប្រើប្រាស់',
    'Users Added' => 'អ្នកប្រើប្រាស់បានបន្ថែម',
    'Add, edit, and manage user accounts and permissions' => 'បន្ថែម កែសម្រួល និងគ្រប់គ្រងគណនីអ្នកប្រើប្រាស់និងសិទ្ធិ',
    'Bulk import users from Excel or CSV files' => 'នាំចូលអ្នកប្រើប្រាស់ច្រើនពីឯកសារ Excel ឬ CSV',
    'Teachers Template' => 'គំរូគ្រូ',
    'Mentors Template' => 'គំរូអ្នកណែនាំ',
    'Total System Users' => 'អ្នកប្រើប្រាស់ប្រព័ន្ធសរុប',
    'Teachers + Mentors + Coordinators' => 'គ្រូ + អ្នកណែនាំ + អ្នកសម្របសម្រួល',

    // Mentoring
    'Mentoring Management' => 'ការគ្រប់គ្រងការណែនាំ',
    'Mentoring Visit Management' => 'ការគ្រប់គ្រងការមកសួរសុខទុក្ខ',
    'Manage mentoring visits and activities' => 'គ្រប់គ្រងការមកសួរសុខទុក្ខនិងសកម្មភាព',
    'Manage Mentoring' => 'គ្រប់គ្រងការណែនាំ',
    'Follow-up Required' => 'ត្រូវការតាមដាន',
    'No mentoring visits found' => 'រកមិនឃើញការមកសួរសុខទុក្ខ',
    'Mentoring Visits' => 'ការមកសួរសុខទុក្ខ',
    'Log Visit' => 'កត់ត្រាការមកសួរ',

    // Dates and periods
    'Baseline assessment period starts on' => 'រយៈពេលវាយតម្លៃមូលដ្ឋានចាប់ផ្តើមនៅ',
    'Midline assessment period starts on' => 'រយៈពេលវាយតម្លៃពាក់កណ្តាលចាប់ផ្តើមនៅ',
    'Endline assessment period starts on' => 'រយៈពេលវាយតម្លៃចុងក្រោយចាប់ផ្តើមនៅ',
    'Baseline assessment period ended on' => 'រយៈពេលវាយតម្លៃមូលដ្ឋានបានបញ្ចប់នៅ',
    'Midline assessment period ended on' => 'រយៈពេលវាយតម្លៃពាក់កណ្តាលបានបញ្ចប់នៅ',
    'Endline assessment period ended on' => 'រយៈពេលវាយតម្លៃចុងក្រោយបានបញ្ចប់នៅ',
    'Baseline assessment period is active until' => 'រយៈពេលវាយតម្លៃមូលដ្ឋានសកម្មរហូតដល់',
    'Midline assessment period is active until' => 'រយៈពេលវាយតម្លៃពាក់កណ្តាលសកម្មរហូតដល់',
    'Endline assessment period is active until' => 'រយៈពេលវាយតម្លៃចុងក្រោយសកម្មរហូតដល់',
    'Assessment dates have not been set for your school. Please contact your administrator.' => 'កាលបរិច្ឆេទវាយតម្លៃមិនទាន់បានកំណត់សម្រាប់សាលារបស់អ្នកទេ។ សូមទាក់ទងអ្នកគ្រប់គ្រងរបស់អ្នក។',
    'Manage Dates' => 'គ្រប់គ្រងកាលបរិច្ឆេទ',

    // Actions and buttons
    'Select All' => 'ជ្រើសរើសទាំងអស់',
    'Deselect All' => 'មិនជ្រើសរើសទាំងអស់',
    'Save Selection' => 'រក្សាទុកការជ្រើសរើស',
    'Lock Selected' => 'ចាក់សោដែលបានជ្រើសរើស',
    'Unlock Selected' => 'ដោះសោដែលបានជ្រើសរើស',
    'Clear Filters' => 'សម្អាតតម្រង',
    'Export to Excel' => 'នាំចេញទៅ Excel',
    'Go to Baseline Assessment' => 'ទៅកាន់ការវាយតម្លៃមូលដ្ឋាន',

    // Dashboard specific
    'Coordinator Dashboard' => 'ផ្ទាំងគ្រប់គ្រងអ្នកសម្របសម្រួល',
    'System Administration' => 'ការគ្រប់គ្រងប្រព័ន្ធ',
    'System overview and data management center' => 'ទិដ្ឋភាពទូទៅប្រព័ន្ធនិងមជ្ឈមណ្ឌលគ្រប់គ្រងទិន្នន័យ',
    'Overall Results' => 'លទ្ធផលរួម',
    'Results by School' => 'លទ្ធផលតាមសាលា',
    'Quick Actions' => 'សកម្មភាពរហ័ស',
    'Loading statistics...' => 'កំពុងផ្ទុកស្ថិតិ...',
    'Loading existing assessments...' => 'កំពុងផ្ទុកការវាយតម្លៃដែលមានស្រាប់...',

    // Import/Export
    'Data Import Center' => 'មជ្ឈមណ្ឌលនាំចូលទិន្នន័យ',
    'Templates' => 'គំរូ',
    'Quick Download' => 'ទាញយករហ័ស',
    'Data Management Access' => 'ការចូលប្រើការគ្រប់គ្រងទិន្នន័យ',

    // Language specific
    'Language Center' => 'មជ្ឈមណ្ឌលភាសា',
    'Available Languages' => 'ភាសាដែលមាន',
    'Current Language' => 'ភាសាបច្ចុប្បន្ន',
    'Switch Language' => 'ប្តូរភាសា',
    'Translation Tools' => 'ឧបករណ៍បកប្រែ',
    'Open Translation Editor' => 'បើកកម្មវិធីកែសម្រួលការបកប្រែ',

    // Status and filters
    'Lock Status' => 'ស្ថានភាពចាក់សោ',
    'All Status' => 'ស្ថានភាពទាំងអស់',
    'All Provinces' => 'ខេត្តទាំងអស់',
    'All Districts' => 'ស្រុកទាំងអស់',
    'All Clusters' => 'ចង្កោមទាំងអស់',
    'All Grades' => 'ថ្នាក់ទាំងអស់',
    'Filter Data' => 'ចម្រាញ់ទិន្នន័យ',

    // Messages
    'Warning' => 'ការព្រមាន',
    'Note' => 'ចំណាំ',
    'Success' => 'ជោគជ័យ',
    'Error' => 'កំហុស',
    'OK' => 'យល់ព្រម',
    'Yes, submit all' => 'បាទ/ចាស ដាក់ស្នើទាំងអស់',
    'Do you want to submit?' => 'តើអ្នកចង់ដាក់ស្នើទេ?',
    'You have assessed' => 'អ្នកបានវាយតម្លៃ',
    'out of' => 'ក្នុងចំណោម',

    // Others
    'Administration' => 'រដ្ឋបាល',
    'Gender' => 'ភេទ',
    'Photo' => 'រូបថត',
    'Version' => 'កំណែ',
    'Environment' => 'បរិស្ថាន',
    'Timezone' => 'ល្វែងម៉ោង',
    'Default Language' => 'ភាសាលំនាំដើម',
    'Credits' => 'ឥណទាន',
    'Current' => 'បច្ចុប្បន្ន',
    'Switch' => 'ប្តូរ',
    'Your Role' => 'តួនាទីរបស់អ្នក',
    'Percentage (%)' => 'ភាគរយ (%)',
    'View Reports' => 'មើលរបាយការណ៍',
    'Help & Support' => 'ជំនួយនិងការគាំទ្រ',
    'Academic Year' => 'ឆ្នាំសិក្សា',
    'Not Assigned' => 'មិនបានផ្តល់',
    'Grade Level' => 'កម្រិតថ្នាក់',
    'Class Name' => 'ឈ្មោះថ្នាក់',
    'Create Class' => 'បង្កើតថ្នាក់',
    'Edit Class' => 'កែសម្រួលថ្នាក់',
    'Update Class' => 'ធ្វើបច្ចុប្បន្នភាពថ្នាក់',
    'Select Grade Level' => 'ជ្រើសរើសកម្រិតថ្នាក់',
    'No classes found.' => 'រកមិនឃើញថ្នាក់។',
    'Log Out' => 'ចាកចេញ',
    'Already registered?' => 'បានចុះឈ្មោះរួចហើយ?',
    'Email Password Reset Link' => 'ផ្ញើតំណពាក្យសម្ងាត់តាមអ៊ីមែល',
    'Reset Password' => 'កំណត់ពាក្យសម្ងាត់ឡើងវិញ',
    'Resend Verification Email' => 'ផ្ញើអ៊ីមែលផ្ទៀងផ្ទាត់ឡើងវិញ',
    'M' => 'ប',
    'F' => 'ស',
];

echo "Updating remaining views translations...\n";
$count = 0;

foreach ($specificTranslations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->where(function ($q) {
            $q->whereNull('km')
                ->orWhere('km', '')
                ->orWhere('km', 'LIKE', '%[%')
                ->orWhereRaw('km = en');
        })
        ->update(['km' => $km]);

    if ($updated > 0) {
        echo "  ✓ Updated: {$en} -> {$km}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} specific views translations.\n";

// Clear cache
Translation::clearCache();

// Check final status
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

echo "\n=== Final Status for Views Group ===\n";
echo "Total translations: {$total}\n";
echo "Completed (with Khmer): {$completed}\n";
echo "Remaining (without Khmer): {$remaining}\n";
echo "Completion: {$percentage}%\n";

if ($remaining > 0) {
    echo "\nShowing first 10 remaining translations that need Khmer:\n";
    $samples = Translation::where('group', 'views')
        ->where(function ($q) {
            $q->whereNull('km')
                ->orWhere('km', '')
                ->orWhere('km', 'LIKE', '%[%')
                ->orWhereRaw('km = en');
        })
        ->limit(10)
        ->pluck('key');

    foreach ($samples as $key) {
        echo "  - {$key}\n";
    }
}
