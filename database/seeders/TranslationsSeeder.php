<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // General
            ['key' => 'app_name', 'en' => 'TaRL Project', 'km' => 'គម្រោង TaRL', 'group' => 'general'],
            ['key' => 'welcome', 'en' => 'Welcome', 'km' => 'សូមស្វាគមន៍', 'group' => 'general'],
            ['key' => 'dashboard', 'en' => 'Dashboard', 'km' => 'ផ្ទាំងគ្រប់គ្រង', 'group' => 'general'],
            ['key' => 'home', 'en' => 'Home', 'km' => 'ទំព័រដើម', 'group' => 'general'],
            ['key' => 'back', 'en' => 'Back', 'km' => 'ត្រឡប់ក្រោយ', 'group' => 'general'],
            ['key' => 'save', 'en' => 'Save', 'km' => 'រក្សាទុក', 'group' => 'general'],
            ['key' => 'cancel', 'en' => 'Cancel', 'km' => 'បោះបង់', 'group' => 'general'],
            ['key' => 'delete', 'en' => 'Delete', 'km' => 'លុប', 'group' => 'general'],
            ['key' => 'edit', 'en' => 'Edit', 'km' => 'កែសម្រួល', 'group' => 'general'],
            ['key' => 'view', 'en' => 'View', 'km' => 'មើល', 'group' => 'general'],
            ['key' => 'create', 'en' => 'Create', 'km' => 'បង្កើត', 'group' => 'general'],
            ['key' => 'update', 'en' => 'Update', 'km' => 'ធ្វើបច្ចុប្បន្នភាព', 'group' => 'general'],
            ['key' => 'search', 'en' => 'Search', 'km' => 'ស្វែងរក', 'group' => 'general'],
            ['key' => 'filter', 'en' => 'Filter', 'km' => 'ត្រង', 'group' => 'general'],
            ['key' => 'export', 'en' => 'Export', 'km' => 'នាំចេញ', 'group' => 'general'],
            ['key' => 'import', 'en' => 'Import', 'km' => 'នាំចូល', 'group' => 'general'],
            ['key' => 'download', 'en' => 'Download', 'km' => 'ទាញយក', 'group' => 'general'],
            ['key' => 'upload', 'en' => 'Upload', 'km' => 'ផ្ទុកឡើង', 'group' => 'general'],
            ['key' => 'submit', 'en' => 'Submit', 'km' => 'ដាក់ស្នើ', 'group' => 'general'],
            ['key' => 'confirm', 'en' => 'Confirm', 'km' => 'បញ្ជាក់', 'group' => 'general'],
            ['key' => 'yes', 'en' => 'Yes', 'km' => 'បាទ/ចាស', 'group' => 'general'],
            ['key' => 'no', 'en' => 'No', 'km' => 'ទេ', 'group' => 'general'],
            ['key' => 'all', 'en' => 'All', 'km' => 'ទាំងអស់', 'group' => 'general'],
            ['key' => 'none', 'en' => 'None', 'km' => 'គ្មាន', 'group' => 'general'],
            ['key' => 'select', 'en' => 'Select', 'km' => 'ជ្រើសរើស', 'group' => 'general'],
            ['key' => 'loading', 'en' => 'Loading...', 'km' => 'កំពុងផ្ទុក...', 'group' => 'general'],
            ['key' => 'no_data', 'en' => 'No data available', 'km' => 'មិនមានទិន្នន័យ', 'group' => 'general'],
            ['key' => 'showing', 'en' => 'Showing', 'km' => 'កំពុងបង្ហាញ', 'group' => 'general'],
            ['key' => 'to', 'en' => 'to', 'km' => 'ដល់', 'group' => 'general'],
            ['key' => 'of', 'en' => 'of', 'km' => 'នៃ', 'group' => 'general'],
            ['key' => 'entries', 'en' => 'entries', 'km' => 'ធាតុ', 'group' => 'general'],
            ['key' => 'actions', 'en' => 'Actions', 'km' => 'សកម្មភាព', 'group' => 'general'],

            // Navigation
            ['key' => 'schools', 'en' => 'Schools', 'km' => 'សាលារៀន', 'group' => 'navigation'],
            ['key' => 'students', 'en' => 'Students', 'km' => 'សិស្ស', 'group' => 'navigation'],
            ['key' => 'teachers', 'en' => 'Teachers', 'km' => 'គ្រូបង្រៀន', 'group' => 'navigation'],
            ['key' => 'mentors', 'en' => 'Mentors', 'km' => 'អ្នកណែនាំ', 'group' => 'navigation'],
            ['key' => 'users', 'en' => 'Users', 'km' => 'អ្នកប្រើប្រាស់', 'group' => 'navigation'],
            ['key' => 'assessments', 'en' => 'Assessments', 'km' => 'ការវាយតម្លៃ', 'group' => 'navigation'],
            ['key' => 'mentoring_visits', 'en' => 'Mentoring Visits', 'km' => 'ការចុះណែនាំ', 'group' => 'navigation'],
            ['key' => 'reports', 'en' => 'Reports', 'km' => 'របាយការណ៍', 'group' => 'navigation'],
            ['key' => 'settings', 'en' => 'Settings', 'km' => 'ការកំណត់', 'group' => 'navigation'],
            ['key' => 'help', 'en' => 'Help', 'km' => 'ជំនួយ', 'group' => 'navigation'],
            ['key' => 'profile', 'en' => 'Profile', 'km' => 'ប្រវត្តិរូប', 'group' => 'navigation'],
            ['key' => 'logout', 'en' => 'Logout', 'km' => 'ចាកចេញ', 'group' => 'navigation'],
            ['key' => 'login', 'en' => 'Login', 'km' => 'ចូល', 'group' => 'navigation'],
            ['key' => 'register', 'en' => 'Register', 'km' => 'ចុះឈ្មោះ', 'group' => 'navigation'],

            // User Management
            ['key' => 'user_management', 'en' => 'User Management', 'km' => 'ការគ្រប់គ្រងអ្នកប្រើប្រាស់', 'group' => 'users'],
            ['key' => 'add_user', 'en' => 'Add User', 'km' => 'បន្ថែមអ្នកប្រើប្រាស់', 'group' => 'users'],
            ['key' => 'edit_user', 'en' => 'Edit User', 'km' => 'កែសម្រួលអ្នកប្រើប្រាស់', 'group' => 'users'],
            ['key' => 'user_details', 'en' => 'User Details', 'km' => 'ព័ត៌មានអ្នកប្រើប្រាស់', 'group' => 'users'],
            ['key' => 'name', 'en' => 'Name', 'km' => 'ឈ្មោះ', 'group' => 'users'],
            ['key' => 'email', 'en' => 'Email', 'km' => 'អ៊ីមែល', 'group' => 'users'],
            ['key' => 'password', 'en' => 'Password', 'km' => 'ពាក្យសម្ងាត់', 'group' => 'users'],
            ['key' => 'role', 'en' => 'Role', 'km' => 'តួនាទី', 'group' => 'users'],
            ['key' => 'phone', 'en' => 'Phone', 'km' => 'ទូរស័ព្ទ', 'group' => 'users'],
            ['key' => 'status', 'en' => 'Status', 'km' => 'ស្ថានភាព', 'group' => 'users'],
            ['key' => 'active', 'en' => 'Active', 'km' => 'សកម្ម', 'group' => 'users'],
            ['key' => 'inactive', 'en' => 'Inactive', 'km' => 'អសកម្ម', 'group' => 'users'],
            ['key' => 'admin', 'en' => 'Admin', 'km' => 'អ្នកគ្រប់គ្រង', 'group' => 'users'],
            ['key' => 'coordinator', 'en' => 'Coordinator', 'km' => 'អ្នកសម្របសម្រួល', 'group' => 'users'],
            ['key' => 'mentor', 'en' => 'Mentor', 'km' => 'អ្នកណែនាំ', 'group' => 'users'],
            ['key' => 'teacher', 'en' => 'Teacher', 'km' => 'គ្រូបង្រៀន', 'group' => 'users'],
            ['key' => 'viewer', 'en' => 'Viewer', 'km' => 'អ្នកមើល', 'group' => 'users'],

            // School Management
            ['key' => 'school_management', 'en' => 'School Management', 'km' => 'ការគ្រប់គ្រងសាលារៀន', 'group' => 'schools'],
            ['key' => 'add_school', 'en' => 'Add School', 'km' => 'បន្ថែមសាលារៀន', 'group' => 'schools'],
            ['key' => 'edit_school', 'en' => 'Edit School', 'km' => 'កែសម្រួលសាលារៀន', 'group' => 'schools'],
            ['key' => 'school_details', 'en' => 'School Details', 'km' => 'ព័ត៌មានសាលារៀន', 'group' => 'schools'],
            ['key' => 'school_name', 'en' => 'School Name', 'km' => 'ឈ្មោះសាលា', 'group' => 'schools'],
            ['key' => 'school_code', 'en' => 'School Code', 'km' => 'លេខកូដសាលា', 'group' => 'schools'],
            ['key' => 'address', 'en' => 'Address', 'km' => 'អាសយដ្ឋាន', 'group' => 'schools'],
            ['key' => 'province', 'en' => 'Province', 'km' => 'ខេត្ត', 'group' => 'schools'],
            ['key' => 'district', 'en' => 'District', 'km' => 'ស្រុក', 'group' => 'schools'],
            ['key' => 'commune', 'en' => 'Commune', 'km' => 'ឃុំ', 'group' => 'schools'],
            ['key' => 'village', 'en' => 'Village', 'km' => 'ភូមិ', 'group' => 'schools'],
            ['key' => 'contact_person', 'en' => 'Contact Person', 'km' => 'អ្នកទំនាក់ទំនង', 'group' => 'schools'],
            ['key' => 'all_schools', 'en' => 'All Schools', 'km' => 'សាលាទាំងអស់', 'group' => 'schools'],
            ['key' => 'all_provinces', 'en' => 'All Provinces', 'km' => 'ខេត្តទាំងអស់', 'group' => 'schools'],
            ['key' => 'all_districts', 'en' => 'All Districts', 'km' => 'ស្រុកទាំងអស់', 'group' => 'schools'],

            // Student Management
            ['key' => 'student_management', 'en' => 'Student Management', 'km' => 'ការគ្រប់គ្រងសិស្ស', 'group' => 'students'],
            ['key' => 'add_student', 'en' => 'Add Student', 'km' => 'បន្ថែមសិស្ស', 'group' => 'students'],
            ['key' => 'edit_student', 'en' => 'Edit Student', 'km' => 'កែសម្រួលសិស្ស', 'group' => 'students'],
            ['key' => 'student_details', 'en' => 'Student Details', 'km' => 'ព័ត៌មានសិស្ស', 'group' => 'students'],
            ['key' => 'student_code', 'en' => 'Student Code', 'km' => 'លេខកូដសិស្ស', 'group' => 'students'],
            ['key' => 'student_name', 'en' => 'Student Name', 'km' => 'ឈ្មោះសិស្ស', 'group' => 'students'],
            ['key' => 'first_name', 'en' => 'First Name', 'km' => 'នាមត្រកូល', 'group' => 'students'],
            ['key' => 'last_name', 'en' => 'Last Name', 'km' => 'នាមខ្លួន', 'group' => 'students'],
            ['key' => 'gender', 'en' => 'Gender', 'km' => 'ភេទ', 'group' => 'students'],
            ['key' => 'male', 'en' => 'Male', 'km' => 'ប្រុស', 'group' => 'students'],
            ['key' => 'female', 'en' => 'Female', 'km' => 'ស្រី', 'group' => 'students'],
            ['key' => 'date_of_birth', 'en' => 'Date of Birth', 'km' => 'ថ្ងៃខែឆ្នាំកំណើត', 'group' => 'students'],
            ['key' => 'age', 'en' => 'Age', 'km' => 'អាយុ', 'group' => 'students'],
            ['key' => 'grade', 'en' => 'Grade', 'km' => 'ថ្នាក់', 'group' => 'students'],
            ['key' => 'class', 'en' => 'Class', 'km' => 'ថ្នាក់រៀន', 'group' => 'students'],
            ['key' => 'parent_name', 'en' => 'Parent Name', 'km' => 'ឈ្មោះឪពុកម្តាយ', 'group' => 'students'],
            ['key' => 'parent_phone', 'en' => 'Parent Phone', 'km' => 'ទូរស័ព្ទឪពុកម្តាយ', 'group' => 'students'],
            ['key' => 'photo', 'en' => 'Photo', 'km' => 'រូបថត', 'group' => 'students'],

            // Assessment
            ['key' => 'assessment_management', 'en' => 'Assessment Management', 'km' => 'ការគ្រប់គ្រងការវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'create_assessment', 'en' => 'Create Assessment', 'km' => 'បង្កើតការវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'edit_assessment', 'en' => 'Edit Assessment', 'km' => 'កែសម្រួលការវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'assessment_details', 'en' => 'Assessment Details', 'km' => 'ព័ត៌មានការវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'baseline', 'en' => 'Baseline', 'km' => 'មូលដ្ឋាន', 'group' => 'assessments'],
            ['key' => 'midline', 'en' => 'Midline', 'km' => 'ពាក់កណ្តាល', 'group' => 'assessments'],
            ['key' => 'endline', 'en' => 'Endline', 'km' => 'ចុងគ្រា', 'group' => 'assessments'],
            ['key' => 'assessment_type', 'en' => 'Assessment Type', 'km' => 'ប្រភេទការវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'subject', 'en' => 'Subject', 'km' => 'មុខវិជ្ជា', 'group' => 'assessments'],
            ['key' => 'math', 'en' => 'Math', 'km' => 'គណិតវិទ្យា', 'group' => 'assessments'],
            ['key' => 'khmer', 'en' => 'Khmer', 'km' => 'ភាសាខ្មែរ', 'group' => 'assessments'],
            ['key' => 'level', 'en' => 'Level', 'km' => 'កម្រិត', 'group' => 'assessments'],
            ['key' => 'letter', 'en' => 'Letter', 'km' => 'អក្សរ', 'group' => 'assessments'],
            ['key' => 'word', 'en' => 'Word', 'km' => 'ពាក្យ', 'group' => 'assessments'],
            ['key' => 'sentence', 'en' => 'Sentence', 'km' => 'ប្រយោគ', 'group' => 'assessments'],
            ['key' => 'paragraph', 'en' => 'Paragraph', 'km' => 'កថាខណ្ឌ', 'group' => 'assessments'],
            ['key' => 'story', 'en' => 'Story', 'km' => 'រឿង', 'group' => 'assessments'],
            ['key' => 'score', 'en' => 'Score', 'km' => 'ពិន្ទុ', 'group' => 'assessments'],
            ['key' => 'assessment_date', 'en' => 'Assessment Date', 'km' => 'កាលបរិច្ឆេទវាយតម្លៃ', 'group' => 'assessments'],
            ['key' => 'assessed_by', 'en' => 'Assessed By', 'km' => 'វាយតម្លៃដោយ', 'group' => 'assessments'],
            ['key' => 'select_students', 'en' => 'Select Students', 'km' => 'ជ្រើសរើសសិស្ស', 'group' => 'assessments'],

            // Mentoring
            ['key' => 'mentoring_management', 'en' => 'Mentoring Management', 'km' => 'ការគ្រប់គ្រងការណែនាំ', 'group' => 'mentoring'],
            ['key' => 'create_visit', 'en' => 'Create Visit', 'km' => 'បង្កើតការចុះមើល', 'group' => 'mentoring'],
            ['key' => 'edit_visit', 'en' => 'Edit Visit', 'km' => 'កែសម្រួលការចុះមើល', 'group' => 'mentoring'],
            ['key' => 'visit_details', 'en' => 'Visit Details', 'km' => 'ព័ត៌មានការចុះមើល', 'group' => 'mentoring'],
            ['key' => 'visit_date', 'en' => 'Visit Date', 'km' => 'កាលបរិច្ឆេទចុះមើល', 'group' => 'mentoring'],
            ['key' => 'observations', 'en' => 'Observations', 'km' => 'ការសង្កេត', 'group' => 'mentoring'],
            ['key' => 'feedback', 'en' => 'Feedback', 'km' => 'មតិយោបល់', 'group' => 'mentoring'],
            ['key' => 'action_items', 'en' => 'Action Items', 'km' => 'ចំណុចត្រូវអនុវត្ត', 'group' => 'mentoring'],
            ['key' => 'follow_up_required', 'en' => 'Follow-up Required', 'km' => 'ត្រូវការតាមដាន', 'group' => 'mentoring'],

            // Reports
            ['key' => 'report_management', 'en' => 'Report Management', 'km' => 'ការគ្រប់គ្រងរបាយការណ៍', 'group' => 'reports'],
            ['key' => 'generate_report', 'en' => 'Generate Report', 'km' => 'បង្កើតរបាយការណ៍', 'group' => 'reports'],
            ['key' => 'performance_report', 'en' => 'Performance Report', 'km' => 'របាយការណ៍លទ្ធផល', 'group' => 'reports'],
            ['key' => 'progress_report', 'en' => 'Progress Report', 'km' => 'របាយការណ៍វឌ្ឍនភាព', 'group' => 'reports'],
            ['key' => 'summary_report', 'en' => 'Summary Report', 'km' => 'របាយការណ៍សង្ខេប', 'group' => 'reports'],
            ['key' => 'detailed_report', 'en' => 'Detailed Report', 'km' => 'របាយការណ៍លម្អិត', 'group' => 'reports'],
            ['key' => 'from_date', 'en' => 'From Date', 'km' => 'ចាប់ពីថ្ងៃ', 'group' => 'reports'],
            ['key' => 'to_date', 'en' => 'To Date', 'km' => 'ដល់ថ្ងៃ', 'group' => 'reports'],
            ['key' => 'total_students', 'en' => 'Total Students', 'km' => 'សិស្សសរុប', 'group' => 'reports'],
            ['key' => 'total_assessments', 'en' => 'Total Assessments', 'km' => 'ការវាយតម្លៃសរុប', 'group' => 'reports'],
            ['key' => 'average_score', 'en' => 'Average Score', 'km' => 'ពិន្ទុមធ្យម', 'group' => 'reports'],
            ['key' => 'improvement_rate', 'en' => 'Improvement Rate', 'km' => 'អត្រាភាពប្រសើរឡើង', 'group' => 'reports'],

            // Messages
            ['key' => 'success', 'en' => 'Success', 'km' => 'ជោគជ័យ', 'group' => 'messages'],
            ['key' => 'error', 'en' => 'Error', 'km' => 'កំហុស', 'group' => 'messages'],
            ['key' => 'warning', 'en' => 'Warning', 'km' => 'ការព្រមាន', 'group' => 'messages'],
            ['key' => 'info', 'en' => 'Information', 'km' => 'ព័ត៌មាន', 'group' => 'messages'],
            ['key' => 'created_successfully', 'en' => 'Created successfully', 'km' => 'បានបង្កើតដោយជោគជ័យ', 'group' => 'messages'],
            ['key' => 'updated_successfully', 'en' => 'Updated successfully', 'km' => 'បានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ', 'group' => 'messages'],
            ['key' => 'deleted_successfully', 'en' => 'Deleted successfully', 'km' => 'បានលុបដោយជោគជ័យ', 'group' => 'messages'],
            ['key' => 'are_you_sure', 'en' => 'Are you sure?', 'km' => 'តើអ្នកប្រាកដទេ?', 'group' => 'messages'],
            ['key' => 'cannot_be_undone', 'en' => 'This action cannot be undone', 'km' => 'សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ', 'group' => 'messages'],
            ['key' => 'please_wait', 'en' => 'Please wait...', 'km' => 'សូមរង់ចាំ...', 'group' => 'messages'],
            ['key' => 'required_field', 'en' => 'This field is required', 'km' => 'ប្រអប់នេះត្រូវការបំពេញ', 'group' => 'messages'],
            ['key' => 'invalid_email', 'en' => 'Invalid email address', 'km' => 'អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវ', 'group' => 'messages'],
            ['key' => 'invalid_password', 'en' => 'Invalid password', 'km' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវ', 'group' => 'messages'],
            ['key' => 'login_success', 'en' => 'Login successful', 'km' => 'ចូលដោយជោគជ័យ', 'group' => 'messages'],
            ['key' => 'logout_success', 'en' => 'Logout successful', 'km' => 'ចាកចេញដោយជោគជ័យ', 'group' => 'messages'],
            ['key' => 'access_denied', 'en' => 'Access denied', 'km' => 'ការចូលត្រូវបានបដិសេធ', 'group' => 'messages'],
            ['key' => 'not_found', 'en' => 'Not found', 'km' => 'រកមិនឃើញ', 'group' => 'messages'],
            ['key' => 'server_error', 'en' => 'Server error', 'km' => 'កំហុសម៉ាស៊ីនមេ', 'group' => 'messages'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                    'group' => $translation['group'],
                    'is_active' => true,
                ]
            );
        }

        // Clear translation cache
        Translation::clearCache();

        $this->command->info('Translations seeded successfully!');
        $this->command->info('Total translations: '.count($translations));
    }
}
