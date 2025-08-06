<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Models Group Translations
$modelTranslations = [
    // User model
    'Name' => 'ឈ្មោះ',
    'Email' => 'អ៊ីមែល',
    'Password' => 'ពាក្យសម្ងាត់',
    'Role' => 'តួនាទី',
    'Phone' => 'ទូរស័ព្ទ',
    'Sex' => 'ភេទ',
    'Gender' => 'ភេទ',
    'Male' => 'ប្រុស',
    'Female' => 'ស្រី',
    'Profile Photo' => 'រូបថតប្រវត្តិរូប',
    'Admin' => 'អ្នកគ្រប់គ្រង',
    'Teacher' => 'គ្រូ',
    'Mentor' => 'អ្នកណែនាំ',
    'Coordinator' => 'អ្នកសម្របសម្រួល',
    'Viewer' => 'អ្នកមើល',
    'User' => 'អ្នកប្រើប្រាស់',
    'Users' => 'អ្នកប្រើប្រាស់',
    'Account' => 'គណនី',
    'Username' => 'ឈ្មោះអ្នកប្រើ',
    'First Name' => 'នាមខ្លួន',
    'Last Name' => 'នាមត្រកូល',
    'Full Name' => 'ឈ្មោះពេញ',
    'Date of Birth' => 'ថ្ងៃខែឆ្នាំកំណើត',
    'Address' => 'អាសយដ្ឋាន',
    'City' => 'ទីក្រុង',
    'Country' => 'ប្រទេស',
    'Nationality' => 'សញ្ជាតិ',
    'Phone Number' => 'លេខទូរស័ព្ទ',
    'Mobile' => 'ទូរស័ព្ទចល័ត',
    'Telephone' => 'ទូរស័ព្ទ',
    'Position' => 'មុខតំណែង',
    'Department' => 'នាយកដ្ឋាន',
    'Permissions' => 'សិទ្ធិ',
    'Last Login' => 'ចូលចុងក្រោយ',
    'Created By' => 'បង្កើតដោយ',
    'Updated By' => 'កែប្រែដោយ',

    // School model
    'School' => 'សាលា',
    'Schools' => 'សាលារៀន',
    'School Name' => 'ឈ្មោះសាលា',
    'School Code' => 'លេខកូដសាលា',
    'Province' => 'ខេត្ត',
    'District' => 'ស្រុក',
    'Commune' => 'ឃុំ',
    'Village' => 'ភូមិ',
    'Cluster' => 'ចង្កោម',
    'Location' => 'ទីតាំង',
    'School Type' => 'ប្រភេទសាលា',
    'Primary School' => 'សាលាបឋមសិក្សា',
    'Secondary School' => 'សាលាមធ្យមសិក្សា',
    'High School' => 'វិទ្យាល័យ',
    'School Director' => 'នាយកសាលា',
    'Contact Person' => 'អ្នកទំនាក់ទំនង',
    'School Phone' => 'ទូរស័ព្ទសាលា',
    'School Email' => 'អ៊ីមែលសាលា',
    'Number of Teachers' => 'ចំនួនគ្រូ',
    'Number of Students' => 'ចំនួនសិស្ស',
    'Academic Year' => 'ឆ្នាំសិក្សា',
    'School Facilities' => 'គ្រឿងបរិក្ខារសាលា',
    'School Address' => 'អាសយដ្ឋានសាលា',

    // Student model
    'Student' => 'សិស្ស',
    'Students' => 'សិស្ស',
    'Student Name' => 'ឈ្មោះសិស្ស',
    'Student Code' => 'លេខកូដសិស្ស',
    'Student ID' => 'អត្តលេខសិស្ស',
    'Age' => 'អាយុ',
    'Grade' => 'ថ្នាក់',
    'Class' => 'ថ្នាក់រៀន',
    'Section' => 'ក្រុម',
    'Student Photo' => 'រូបថតសិស្ស',
    'Parent Name' => 'ឈ្មោះមាតាបិតា',
    'Guardian Name' => 'ឈ្មោះអាណាព្យាបាល',
    'Parent Phone' => 'ទូរស័ព្ទមាតាបិតា',
    'Guardian Phone' => 'ទូរស័ព្ទអាណាព្យាបាល',
    'Home Address' => 'អាសយដ្ឋានផ្ទះ',
    'Enrollment Date' => 'ថ្ងៃចុះឈ្មោះ',
    'Enrollment Status' => 'ស្ថានភាពការចុះឈ្មោះ',
    'Active Student' => 'សិស្សសកម្ម',
    'Inactive Student' => 'សិស្សអសកម្ម',
    'Dropped Out' => 'បោះបង់ការសិក្សា',
    'Transferred' => 'ផ្ទេរសាលា',
    'Graduated' => 'បានបញ្ចប់',
    'Special Needs' => 'តម្រូវការពិសេស',
    'Medical Conditions' => 'ស្ថានភាពសុខភាព',
    'Emergency Contact' => 'ទំនាក់ទំនងពេលអាសន្ន',

    // Assessment model
    'Assessment' => 'ការវាយតម្លៃ',
    'Assessments' => 'ការវាយតម្លៃ',
    'Assessment Date' => 'កាលបរិច្ឆេទវាយតម្លៃ',
    'Assessment Type' => 'ប្រភេទវាយតម្លៃ',
    'Subject' => 'មុខវិជ្ជា',
    'Level' => 'កម្រិត',
    'Cycle' => 'វដ្ត',
    'Score' => 'ពិន្ទុ',
    'Result' => 'លទ្ធផល',
    'Baseline' => 'មូលដ្ឋាន',
    'Midline' => 'ពាក់កណ្តាល',
    'Endline' => 'ចុងក្រោយ',
    'Khmer' => 'ភាសាខ្មែរ',
    'Math' => 'គណិតវិទ្យា',
    'Mathematics' => 'គណិតវិទ្យា',
    'English' => 'ភាសាអង់គ្លេស',
    'Science' => 'វិទ្យាសាស្ត្រ',
    'Social Studies' => 'សិក្សាសង្គម',
    'Beginner' => 'ចាប់ផ្តើម',
    'Letter' => 'អក្សរ',
    'Word' => 'ពាក្យ',
    'Sentence' => 'ប្រយោគ',
    'Paragraph' => 'កថាខណ្ឌ',
    'Story' => 'រឿង',
    'Addition' => 'ការបូក',
    'Subtraction' => 'ការដក',
    'Multiplication' => 'ការគុណ',
    'Division' => 'ការចែក',
    'Problem Solving' => 'ការដោះស្រាយបញ្ហា',
    'Pass' => 'ជាប់',
    'Fail' => 'ធ្លាក់',
    'Absent' => 'អវត្តមាន',
    'Present' => 'វត្តមាន',
    'Performance' => 'សមិទ្ធផល',
    'Progress' => 'វឌ្ឍនភាព',
    'Improvement' => 'ការកែលម្អ',
    'Achievement' => 'សមិទ្ធិផល',
    'Competency' => 'សមត្ថភាព',
    'Learning Outcome' => 'លទ្ធផលសិក្សា',

    // Mentoring model
    'Mentoring' => 'ការណែនាំ',
    'Mentoring Visit' => 'ការមកសួរសុខទុក្ខ',
    'Mentoring Visits' => 'ការមកសួរសុខទុក្ខ',
    'Visit Date' => 'កាលបរិច្ឆេទទស្សនា',
    'Visit Time' => 'ពេលវេលាទស្សនា',
    'Visit Purpose' => 'គោលបំណងទស្សនា',
    'Observation Notes' => 'កំណត់ចំណាំការសង្កេត',
    'Feedback' => 'មតិយោបល់',
    'Recommendations' => 'អនុសាសន៍',
    'Next Steps' => 'ជំហានបន្ទាប់',
    'Follow Up' => 'តាមដាន',
    'Teaching Methods' => 'វិធីសាស្ត្របង្រៀន',
    'Classroom Management' => 'ការគ្រប់គ្រងថ្នាក់រៀន',
    'Student Engagement' => 'ការចូលរួមរបស់សិស្ស',
    'Learning Environment' => 'បរិយាកាសសិក្សា',
    'Resources Used' => 'ធនធានប្រើប្រាស់',
    'Challenges Faced' => 'បញ្ហាប្រឈម',
    'Support Needed' => 'ការគាំទ្រត្រូវការ',
    'Best Practices' => 'ការអនុវត្តល្អបំផុត',
    'Areas for Improvement' => 'ចំណុចត្រូវកែលម្អ',
    'Action Plan' => 'ផែនការសកម្មភាព',
    'Timeline' => 'ពេលវេលា',
    'Mentor Notes' => 'កំណត់ចំណាំអ្នកណែនាំ',
    'Teacher Response' => 'ការឆ្លើយតបរបស់គ្រូ',
    'Principal Comments' => 'មតិយោបល់នាយកសាលា',
];

echo "Updating model translations...\n";
$count = 0;

foreach ($modelTranslations as $en => $km) {
    Translation::where('key', $en)
        ->where('group', 'models')
        ->update(['km' => $km]);
    $count++;
}

echo "Updated {$count} model translations.\n";

// Clear cache
Translation::clearCache();
echo "Cache cleared.\n";
