<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class ComprehensiveTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Dashboard specific
            ['key' => 'filter_data', 'en' => 'Filter Data', 'km' => 'ត្រងទិន្នន័យ', 'group' => 'dashboard'],
            ['key' => 'all_provinces', 'en' => 'All Provinces', 'km' => 'ខេត្តទាំងអស់', 'group' => 'geographic'],
            ['key' => 'all_districts', 'en' => 'All Districts', 'km' => 'ស្រុកទាំងអស់', 'group' => 'geographic'],
            ['key' => 'all_communes', 'en' => 'All Communes', 'km' => 'ឃុំទាំងអស់', 'group' => 'geographic'],
            ['key' => 'all_villages', 'en' => 'All Villages', 'km' => 'ភូមិទាំងអស់', 'group' => 'geographic'],
            ['key' => 'total_students', 'en' => 'Total Students', 'km' => 'សិស្សសរុប', 'group' => 'dashboard'],
            ['key' => 'total_teachers', 'en' => 'Total Teachers', 'km' => 'គ្រូសរុប', 'group' => 'dashboard'],
            ['key' => 'total_schools', 'en' => 'Total Schools', 'km' => 'សាលាសរុប', 'group' => 'dashboard'],
            ['key' => 'total_assessments', 'en' => 'Total Assessments', 'km' => 'ការវាយតម្លៃសរុប', 'group' => 'dashboard'],
            ['key' => 'mentoring_visits', 'en' => 'Mentoring Visits', 'km' => 'ការទស្សនកិច្ចណែនាំ', 'group' => 'dashboard'],
            ['key' => 'recent_assessments', 'en' => 'Recent Assessments', 'km' => 'ការវាយតម្លៃថ្មីៗ', 'group' => 'dashboard'],
            ['key' => 'performance_by_level', 'en' => 'Performance by Level', 'km' => 'លទ្ធផលតាមកម្រិត', 'group' => 'dashboard'],
            ['key' => 'assessment_distribution', 'en' => 'Assessment Distribution', 'km' => 'ការបែងចែកការវាយតម្លៃ', 'group' => 'dashboard'],
            
            // Student Management
            ['key' => 'add_student', 'en' => 'Add Student', 'km' => 'បន្ថែមសិស្ស', 'group' => 'student'],
            ['key' => 'edit_student', 'en' => 'Edit Student', 'km' => 'កែសម្រួលសិស្ស', 'group' => 'student'],
            ['key' => 'delete_student', 'en' => 'Delete Student', 'km' => 'លុបសិស្ស', 'group' => 'student'],
            ['key' => 'student_details', 'en' => 'Student Details', 'km' => 'ព័ត៌មានលម្អិតសិស្ស', 'group' => 'student'],
            ['key' => 'student_code', 'en' => 'Student Code', 'km' => 'លេខកូដសិស្ស', 'group' => 'student'],
            ['key' => 'student_photo', 'en' => 'Student Photo', 'km' => 'រូបថតសិស្ស', 'group' => 'student'],
            ['key' => 'guardian_name', 'en' => 'Guardian Name', 'km' => 'ឈ្មោះអាណាព្យាបាល', 'group' => 'student'],
            ['key' => 'guardian_phone', 'en' => 'Guardian Phone', 'km' => 'ទូរស័ព្ទអាណាព្យាបាល', 'group' => 'student'],
            ['key' => 'nickname', 'en' => 'Nickname', 'km' => 'ឈ្មោះហៅក្រៅ', 'group' => 'student'],
            ['key' => 'birthdate', 'en' => 'Birthdate', 'km' => 'ថ្ងៃខែឆ្នាំកំណើត', 'group' => 'student'],
            ['key' => 'age', 'en' => 'Age', 'km' => 'អាយុ', 'group' => 'student'],
            ['key' => 'section', 'en' => 'Section', 'km' => 'ផ្នែក', 'group' => 'student'],
            ['key' => 'student_number', 'en' => 'Student Number', 'km' => 'លេខរៀងសិស្ស', 'group' => 'student'],
            
            // School Management
            ['key' => 'add_school', 'en' => 'Add School', 'km' => 'បន្ថែមសាលា', 'group' => 'school'],
            ['key' => 'edit_school', 'en' => 'Edit School', 'km' => 'កែសម្រួលសាលា', 'group' => 'school'],
            ['key' => 'delete_school', 'en' => 'Delete School', 'km' => 'លុបសាលា', 'group' => 'school'],
            ['key' => 'school_details', 'en' => 'School Details', 'km' => 'ព័ត៌មានលម្អិតសាលា', 'group' => 'school'],
            ['key' => 'school_code', 'en' => 'School Code', 'km' => 'លេខកូដសាលា', 'group' => 'school'],
            ['key' => 'school_name', 'en' => 'School Name', 'km' => 'ឈ្មោះសាលា', 'group' => 'school'],
            ['key' => 'contact_person', 'en' => 'Contact Person', 'km' => 'អ្នកទំនាក់ទំនង', 'group' => 'school'],
            ['key' => 'education_service_area', 'en' => 'Education Service Area', 'km' => 'តំបន់សេវាអប់រំ', 'group' => 'school'],
            
            // User Management
            ['key' => 'add_user', 'en' => 'Add User', 'km' => 'បន្ថែមអ្នកប្រើ', 'group' => 'user'],
            ['key' => 'edit_user', 'en' => 'Edit User', 'km' => 'កែសម្រួលអ្នកប្រើ', 'group' => 'user'],
            ['key' => 'delete_user', 'en' => 'Delete User', 'km' => 'លុបអ្នកប្រើ', 'group' => 'user'],
            ['key' => 'user_details', 'en' => 'User Details', 'km' => 'ព័ត៌មានលម្អិតអ្នកប្រើ', 'group' => 'user'],
            ['key' => 'users', 'en' => 'Users', 'km' => 'អ្នកប្រើប្រាស់', 'group' => 'user'],
            ['key' => 'role', 'en' => 'Role', 'km' => 'តួនាទី', 'group' => 'user'],
            ['key' => 'password', 'en' => 'Password', 'km' => 'ពាក្យសម្ងាត់', 'group' => 'user'],
            ['key' => 'confirm_password', 'en' => 'Confirm Password', 'km' => 'បញ្ជាក់ពាក្យសម្ងាត់', 'group' => 'user'],
            ['key' => 'assign_schools', 'en' => 'Assign Schools', 'km' => 'ចាត់តាំងសាលា', 'group' => 'user'],
            ['key' => 'holding_classes', 'en' => 'Holding Classes', 'km' => 'ថ្នាក់ដែលបង្រៀន', 'group' => 'user'],
            ['key' => 'sex', 'en' => 'Sex', 'km' => 'ភេទ', 'group' => 'user'],
            
            // Classes
            ['key' => 'classes', 'en' => 'Classes', 'km' => 'ថ្នាក់រៀន', 'group' => 'class'],
            ['key' => 'add_class', 'en' => 'Add Class', 'km' => 'បន្ថែមថ្នាក់', 'group' => 'class'],
            ['key' => 'edit_class', 'en' => 'Edit Class', 'km' => 'កែសម្រួលថ្នាក់', 'group' => 'class'],
            ['key' => 'delete_class', 'en' => 'Delete Class', 'km' => 'លុបថ្នាក់', 'group' => 'class'],
            ['key' => 'class_name', 'en' => 'Class Name', 'km' => 'ឈ្មោះថ្នាក់', 'group' => 'class'],
            ['key' => 'academic_year', 'en' => 'Academic Year', 'km' => 'ឆ្នាំសិក្សា', 'group' => 'class'],
            
            // Reports
            ['key' => 'generate_report', 'en' => 'Generate Report', 'km' => 'បង្កើតរបាយការណ៍', 'group' => 'report'],
            ['key' => 'export_report', 'en' => 'Export Report', 'km' => 'នាំចេញរបាយការណ៍', 'group' => 'report'],
            ['key' => 'student_performance', 'en' => 'Student Performance', 'km' => 'លទ្ធផលសិស្ស', 'group' => 'report'],
            ['key' => 'progress_tracking', 'en' => 'Progress Tracking', 'km' => 'តាមដានវឌ្ឍនភាព', 'group' => 'report'],
            ['key' => 'mentoring_impact', 'en' => 'Mentoring Impact', 'km' => 'ផលប៉ះពាល់នៃការណែនាំ', 'group' => 'report'],
            ['key' => 'my_mentoring', 'en' => 'My Mentoring', 'km' => 'ការណែនាំរបស់ខ្ញុំ', 'group' => 'report'],
            ['key' => 'performance_calculation', 'en' => 'Performance Calculation', 'km' => 'ការគណនាលទ្ធផល', 'group' => 'report'],
            
            // Import/Export
            ['key' => 'import_data', 'en' => 'Import Data', 'km' => 'នាំចូលទិន្នន័យ', 'group' => 'import'],
            ['key' => 'export_data', 'en' => 'Export Data', 'km' => 'នាំចេញទិន្នន័យ', 'group' => 'import'],
            ['key' => 'import_students', 'en' => 'Import Students', 'km' => 'នាំចូលសិស្ស', 'group' => 'import'],
            ['key' => 'import_teachers', 'en' => 'Import Teachers', 'km' => 'នាំចូលគ្រូ', 'group' => 'import'],
            ['key' => 'import_schools', 'en' => 'Import Schools', 'km' => 'នាំចូលសាលា', 'group' => 'import'],
            ['key' => 'download_template', 'en' => 'Download Template', 'km' => 'ទាញយកគំរូ', 'group' => 'import'],
            ['key' => 'upload_file', 'en' => 'Upload File', 'km' => 'ផ្ទុកឯកសារ', 'group' => 'import'],
            ['key' => 'select_file', 'en' => 'Select File', 'km' => 'ជ្រើសរើសឯកសារ', 'group' => 'import'],
            
            // Validation messages
            ['key' => 'field_required', 'en' => 'This field is required', 'km' => 'ទិន្នន័យនេះចាំបាច់', 'group' => 'validation'],
            ['key' => 'invalid_email', 'en' => 'Invalid email address', 'km' => 'អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវ', 'group' => 'validation'],
            ['key' => 'password_mismatch', 'en' => 'Passwords do not match', 'km' => 'ពាក្យសម្ងាត់មិនត្រូវគ្នា', 'group' => 'validation'],
            ['key' => 'data_saved', 'en' => 'Data saved successfully', 'km' => 'ទិន្នន័យត្រូវបានរក្សាទុកដោយជោគជ័យ', 'group' => 'validation'],
            ['key' => 'data_deleted', 'en' => 'Data deleted successfully', 'km' => 'ទិន្នន័យត្រូវបានលុបដោយជោគជ័យ', 'group' => 'validation'],
            ['key' => 'operation_failed', 'en' => 'Operation failed', 'km' => 'ប្រតិបត្តិការបរាជ័យ', 'group' => 'validation'],
            
            // Months
            ['key' => 'january', 'en' => 'January', 'km' => 'មករា', 'group' => 'date'],
            ['key' => 'february', 'en' => 'February', 'km' => 'កុម្ភៈ', 'group' => 'date'],
            ['key' => 'march', 'en' => 'March', 'km' => 'មីនា', 'group' => 'date'],
            ['key' => 'april', 'en' => 'April', 'km' => 'មេសា', 'group' => 'date'],
            ['key' => 'may', 'en' => 'May', 'km' => 'ឧសភា', 'group' => 'date'],
            ['key' => 'june', 'en' => 'June', 'km' => 'មិថុនា', 'group' => 'date'],
            ['key' => 'july', 'en' => 'July', 'km' => 'កក្កដា', 'group' => 'date'],
            ['key' => 'august', 'en' => 'August', 'km' => 'សីហា', 'group' => 'date'],
            ['key' => 'september', 'en' => 'September', 'km' => 'កញ្ញា', 'group' => 'date'],
            ['key' => 'october', 'en' => 'October', 'km' => 'តុលា', 'group' => 'date'],
            ['key' => 'november', 'en' => 'November', 'km' => 'វិច្ឆិកា', 'group' => 'date'],
            ['key' => 'december', 'en' => 'December', 'km' => 'ធ្នូ', 'group' => 'date'],
            
            // Days
            ['key' => 'monday', 'en' => 'Monday', 'km' => 'ច័ន្ទ', 'group' => 'date'],
            ['key' => 'tuesday', 'en' => 'Tuesday', 'km' => 'អង្គារ', 'group' => 'date'],
            ['key' => 'wednesday', 'en' => 'Wednesday', 'km' => 'ពុធ', 'group' => 'date'],
            ['key' => 'thursday', 'en' => 'Thursday', 'km' => 'ព្រហស្បតិ៍', 'group' => 'date'],
            ['key' => 'friday', 'en' => 'Friday', 'km' => 'សុក្រ', 'group' => 'date'],
            ['key' => 'saturday', 'en' => 'Saturday', 'km' => 'សៅរ៍', 'group' => 'date'],
            ['key' => 'sunday', 'en' => 'Sunday', 'km' => 'អាទិត្យ', 'group' => 'date'],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                [
                    'en' => $translation['en'],
                    'km' => $translation['km'],
                    'group' => $translation['group'],
                ]
            );
        }

        // Clear translation cache
        Translation::clearCache();

        $this->command->info('Comprehensive translations seeded successfully!');
        $this->command->info('Total translations added: ' . count($translations));
    }
}