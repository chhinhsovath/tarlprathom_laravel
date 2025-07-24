<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Translation;

// Batch 3: Long descriptions, instructions, and UI messages
$batch3Translations = [
    // Login and authentication
    'Log in' => 'ចូលប្រើប្រាស់',
    'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.' => 'ភ្លេចពាក្យសម្ងាត់? មិនមានបញ្ហាទេ។ គ្រាន់តែប្រាប់យើងនូវអាសយដ្ឋានអ៊ីមែលរបស់អ្នក ហើយយើងនឹងផ្ញើតំណកំណត់ពាក្យសម្ងាត់ឡើងវិញទៅអ្នក។',
    'A new verification link has been sent to the email address you provided during registration.' => 'តំណផ្ទៀងផ្ទាត់ថ្មីត្រូវបានផ្ញើទៅអាសយដ្ឋានអ៊ីមែលដែលអ្នកបានផ្តល់ពេលចុះឈ្មោះ។',
    
    // System descriptions
    'The TaRL Assessment System is a comprehensive educational platform designed to help teachers and mentors track student progress and improve learning outcomes through data-driven insights.' => 'ប្រព័ន្ធវាយតម្លៃ TaRL គឺជាវេទិកាអប់រំទូលំទូលាយដែលត្រូវបានរចនាឡើងដើម្បីជួយគ្រូនិងអ្នកណែនាំតាមដានវឌ្ឍនភាពសិស្សនិងកែលម្អលទ្ធផលសិក្សាតាមរយៈការយល់ដឹងផ្អែកលើទិន្នន័យ។',
    'Our Mission' => 'បេសកកម្មរបស់យើង',
    'To empower educators with tools that enable them to assess students effectively, identify learning gaps, and provide targeted interventions that help every child learn at their appropriate level.' => 'ផ្តល់អំណាចដល់អ្នកអប់រំជាមួយឧបករណ៍ដែលអាចឱ្យពួកគេវាយតម្លៃសិស្សប្រកបដោយប្រសិទ្ធភាព កំណត់គម្លាតការសិក្សា និងផ្តល់អន្តរាគមន៍គោលដៅដែលជួយកុមារគ្រប់រូបរៀនតាមកម្រិតសមស្របរបស់ពួកគេ។',
    
    // Features
    'Key Features' => 'លក្ខណៈសំខាន់ៗ',
    'Comprehensive student assessment tracking' => 'ការតាមដានការវាយតម្លៃសិស្សយ៉ាងទូលំទូលាយ',
    'Real-time performance analytics and reporting' => 'ការវិភាគនិងរបាយការណ៍សមិទ្ធផលតាមពេលវេលាជាក់ស្តែង',
    'Multi-school management capabilities' => 'សមត្ថភាពគ្រប់គ្រងសាលាច្រើន',
    'Mentoring visit documentation' => 'ឯកសារការមកសួរសុខទុក្ខរបស់អ្នកណែនាំ',
    'Progress tracking across multiple assessment cycles' => 'ការតាមដានវឌ្ឍនភាពឆ្លងកាត់វដ្តវាយតម្លៃច្រើន',
    'Export capabilities for offline analysis' => 'សមត្ថភាពនាំចេញសម្រាប់ការវិភាគក្រៅបណ្តាញ',
    
    // System information
    'System Information' => 'ព័ត៌មានប្រព័ន្ធ',
    'Laravel Version' => 'កំណែ Laravel',
    'PHP Version' => 'កំណែ PHP',
    'TaRL Project. All rights reserved.' => 'គម្រោង TaRL។ រក្សាសិទ្ធិគ្រប់យ៉ាង។',
    
    // Resource management
    'Manage Resources' => 'គ្រប់គ្រងធនធាន',
    'Upload and manage educational resources' => 'ផ្ទុកឡើងនិងគ្រប់គ្រងធនធានអប់រំ',
    
    // Level progressions
    'Levels' => 'កម្រិត',
    'Beg → Let → Wrd → Par → Sto → C1 → C2' => 'ចាប់ផ្តើម → អក្សរ → ពាក្យ → កថាខណ្ឌ → រឿង → សមាសភាគ១ → សមាសភាគ២',
    'Beg → 1D → 2D → Sub → Div → WP' => 'ចាប់ផ្តើម → ១ខ្ទង់ → ២ខ្ទង់ → ដក → ចែក → ល្បាកលេខ',
    
    // Search placeholders
    'Search by student name...' => 'ស្វែងរកតាមឈ្មោះសិស្ស...',
    'Search by name...' => 'ស្វែងរកតាមឈ្មោះ...',
    'Search by teacher, mentor, school or notes...' => 'ស្វែងរកតាមគ្រូ អ្នកណែនាំ សាលា ឬកំណត់ចំណាំ...',
    
    // Baseline specific
    'Baseline Khmer' => 'ភាសាខ្មែរមូលដ្ឋាន',
    'Baseline Math' => 'គណិតវិទ្យាមូលដ្ឋាន',
    
    // Class related
    'e.g., Section A, Section B, Morning Class, etc.' => 'ឧ. ផ្នែក ក, ផ្នែក ខ, ថ្នាក់ពេលព្រឹក ។ល។',
    'Assign Teacher (Optional)' => 'ចាត់តាំងគ្រូ (ស្រេចចិត្ត)',
    'Academic Year (Optional)' => 'ឆ្នាំសិក្សា (ស្រេចចិត្ត)',
    '2024-2025' => '២០២៤-២០២៥',
    
    // Activity tracking
    'This Month Activity' => 'សកម្មភាពខែនេះ',
    'Today Activity' => 'សកម្មភាពថ្ងៃនេះ',
    
    // Assessment period messages
    ':count assessment(s) are locked by administrators and cannot be edited.' => 'ការវាយតម្លៃចំនួន :count ត្រូវបានចាក់សោដោយអ្នកគ្រប់គ្រងហើយមិនអាចកែសម្រួលបានទេ។',
    'No students from baseline assessment (Beginner to Story level) found for this cycle.' => 'រកមិនឃើញសិស្សពីការវាយតម្លៃមូលដ្ឋាន (កម្រិតចាប់ផ្តើមដល់រឿង) សម្រាប់វដ្តនេះទេ។',
    'No students from baseline assessment (Beginner to Subtraction level) found for this cycle.' => 'រកមិនឃើញសិស្សពីការវាយតម្លៃមូលដ្ឋាន (កម្រិតចាប់ផ្តើមដល់ការដក) សម្រាប់វដ្តនេះទេ។',
    
    // Help messages
    'Welcome to TaRL Assessment System Help' => 'សូមស្វាគមន៍មកកាន់ជំនួយប្រព័ន្ធវាយតម្លៃ TaRL',
    'This system is designed specifically for Grade 4 and Grade 5 students only.' => 'ប្រព័ន្ធនេះត្រូវបានរចនាឡើងជាពិសេសសម្រាប់តែសិស្សថ្នាក់ទី៤ និងថ្នាក់ទី៥ប៉ុណ្ណោះ។',
    
    // Confirmation messages
    'This is a secure area of the application. Please confirm your password before continuing.' => 'នេះជាតំបន់សុវត្ថិភាពនៃកម្មវិធី។ សូមបញ្ជាក់ពាក្យសម្ងាត់របស់អ្នកមុនពេលបន្ត។',
    'Are you sure you want to delete this class?' => 'តើអ្នកប្រាកដថាចង់លុបថ្នាក់នេះទេ?',
    'Are you sure you want to delete this student?' => 'តើអ្នកប្រាកដថាចង់លុបសិស្សនេះទេ?',
    
    // Instructions
    'Please select both gender and level' => 'សូមជ្រើសរើសទាំងភេទនិងកម្រិត',
    'Select which students should be assessed for :type. Only selected students will appear in the assessment list.' => 'ជ្រើសរើសសិស្សដែលគួរត្រូវបានវាយតម្លៃសម្រាប់ :type។ មានតែសិស្សដែលបានជ្រើសរើសប៉ុណ្ណោះនឹងបង្ហាញក្នុងបញ្ជីវាយតម្លៃ។',
    
    // Student info messages
    'Students in this Class' => 'សិស្សក្នុងថ្នាក់នេះ',
    'No students in this class yet.' => 'មិនទាន់មានសិស្សក្នុងថ្នាក់នេះនៅឡើយទេ។',
    'Class Information' => 'ព័ត៌មានថ្នាក់',
    
    // Dashboard messages
    'System overview and data management center' => 'ទិដ្ឋភាពទូទៅប្រព័ន្ធនិងមជ្ឈមណ្ឌលគ្រប់គ្រងទិន្នន័យ',
    'Loading statistics...' => 'កំពុងផ្ទុកស្ថិតិ...',
    
    // Import related
    'Import Teacher/Mentor' => 'នាំចូលគ្រូ/អ្នកណែនាំ',
    'Download Template' => 'ទាញយកគំរូ',
    'Instructions' => 'សេចក្តីណែនាំ',
    'Back to Students' => 'ត្រឡប់ទៅសិស្ស',
    'Import Students from Excel' => 'នាំចូលសិស្សពី Excel',
    'Choose Excel File' => 'ជ្រើសរើសឯកសារ Excel',
    'Select Schools' => 'ជ្រើសរើសសាលា',
    'Import Summary' => 'សង្ខេបការនាំចូល',
    'Import Complete' => 'ការនាំចូលបានបញ្ចប់',
    'Processing...' => 'កំពុងដំណើរការ...',
    'records imported successfully' => 'កំណត់ត្រាបាននាំចូលដោយជោគជ័យ',
    'records failed' => 'កំណត់ត្រាបានបរាជ័យ',
    'Failed Records' => 'កំណត់ត្រាដែលបរាជ័យ',
    'Row' => 'ជួរ',
    'Reason' => 'មូលហេតុ',
    'Start Import' => 'ចាប់ផ្តើមនាំចូល',
    'Back to Import' => 'ត្រឡប់ទៅនាំចូល',
    
    // Report types
    'Student Performance Report' => 'របាយការណ៍សមិទ្ធផលសិស្ស',
    'Progress Tracking Report' => 'របាយការណ៍តាមដានវឌ្ឍនភាព',
    'School Comparison Report' => 'របាយការណ៍ប្រៀបធៀបសាលា',
    'Mentoring Impact Report' => 'របាយការណ៍ផលប៉ះពាល់នៃការណែនាំ',
    'My Mentoring Visits' => 'ការមកសួរសុខទុក្ខរបស់ខ្ញុំ',
    
    // Mentoring specific
    'Create Mentoring Visit' => 'បង្កើតការមកសួរសុខទុក្ខ',
    'Edit Mentoring Visit' => 'កែសម្រួលការមកសួរសុខទុក្ខ',
    'Mentoring Visit Details' => 'ព័ត៌មានលម្អិតការមកសួរសុខទុក្ខ',
    'Observation Notes' => 'កំណត់ចំណាំការសង្កេត',
    'Feedback Given' => 'មតិយោបល់ដែលបានផ្តល់',
    'Areas of Improvement' => 'ចំណុចត្រូវកែលម្អ',
    'Strengths Observed' => 'ចំណុចខ្លាំងដែលបានសង្កេត',
    'Next Visit Date' => 'កាលបរិច្ឆេទមកសួរលើកក្រោយ',
    
    // School specific
    'Create School' => 'បង្កើតសាលា',
    'Edit School' => 'កែសម្រួលសាលា',
    'School Details' => 'ព័ត៌មានលម្អិតសាលា',
    'Total Teachers' => 'គ្រូសរុប',
    'Assessment Statistics' => 'ស្ថិតិការវាយតម្លៃ',
    'School Performance' => 'សមិទ្ធផលសាលា',
    'Assessment Schedule' => 'កាលវិភាគវាយតម្លៃ',
    
    // Settings specific
    'Application Settings' => 'ការកំណត់កម្មវិធី',
    'Email Settings' => 'ការកំណត់អ៊ីមែល',
    'Security Settings' => 'ការកំណត់សុវត្ថិភាព',
    'Save Settings' => 'រក្សាទុកការកំណត់',
    'Settings updated successfully' => 'ការកំណត់បានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ',
    
    // Resource related
    'Create Resource' => 'បង្កើតធនធាន',
    'Edit Resource' => 'កែសម្រួលធនធាន',
    'Resource Details' => 'ព័ត៌មានលម្អិតធនធាន',
    'Resource Title' => 'ចំណងជើងធនធាន',
    'Resource Description' => 'ការពិពណ៌នាធនធាន',
    'Resource Type' => 'ប្រភេទធនធាន',
    'Upload Resource' => 'ផ្ទុកធនធានឡើង',
    'Download Resource' => 'ទាញយកធនធាន',
    'Public Resources' => 'ធនធានសាធារណៈ',
    
    // Profile related
    'Profile Information' => 'ព័ត៌មានប្រវត្តិរូប',
    'Update Profile Information' => 'ធ្វើបច្ចុប្បន្នភាពព័ត៌មានប្រវត្តិរូប',
    'Update Password' => 'ធ្វើបច្ចុប្បន្នភាពពាក្យសម្ងាត់',
    'Delete Account' => 'លុបគណនី',
    'Once your account is deleted, all of its resources and data will be permanently deleted.' => 'នៅពេលគណនីរបស់អ្នកត្រូវបានលុប ធនធាននិងទិន្នន័យទាំងអស់របស់វានឹងត្រូវបានលុបជាអចិន្ត្រៃយ៍។',
];

echo "Updating views translations batch 3...\n";
$count = 0;

foreach ($batch3Translations as $en => $km) {
    $updated = Translation::where('key', $en)
        ->where('group', 'views')
        ->update(['km' => $km]);
    
    if ($updated > 0) {
        echo "  ✓ Updated: {$en}\n";
        $count += $updated;
    }
}

echo "\nUpdated {$count} translations in batch 3.\n";

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

echo "\nRemaining untranslated in views group: {$remaining}\n";