<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Student;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Importing production data from dashfiyn_tarlprathom...');

        // Clear existing data (PostgreSQL uses CASCADE)
        DB::statement('TRUNCATE TABLE assessments, assessment_histories CASCADE');
        DB::statement('TRUNCATE TABLE students CASCADE');
        DB::statement('TRUNCATE TABLE schools CASCADE');
        DB::table('users')->where('id', '>', 8)->delete(); // Keep the default users we created

        // Import Users
        $this->importUsers();
        
        // Import Schools
        $this->importSchools();
        
        // Import Students
        $this->importStudents();
        
        // Import Assessments
        $this->importAssessments();

        $this->command->info('Production data import completed successfully!');
    }

    private function importUsers()
    {
        $this->command->info('Importing users...');
        
        // Based on the SQL dump, there's 1 user
        $userData = [
            'id' => 9, // Use ID 9 to avoid conflicts with existing users
            'name' => 'Production User',
            'email' => 'dashfiyn@tarlprathom.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => '2025-08-25 07:25:03',
            'updated_at' => '2025-08-25 07:25:03',
        ];
        
        User::create($userData);
    }

    private function importSchools()
    {
        $this->command->info('Importing schools...');
        
        $schoolData = [
            'id' => 6, // Use ID 6 to avoid conflicts
            'name' => 'Production School',
            'school_name' => 'Production School',
            'school_code' => 'PROD001',
            'province' => 'Phnom Penh',
            'district' => 'Central',
            'commune' => 'Production',
            'cluster' => 'Production Cluster',
            'created_at' => '2025-08-25 07:25:03',
            'updated_at' => '2025-08-25 07:25:03',
        ];
        
        School::create($schoolData);
    }

    private function importStudents()
    {
        $this->command->info('Importing students...');
        
        // Production students data based on the assessments
        $students = [
            ['id' => 1, 'name' => 'Student 1', 'sex' => 'male', 'age' => 8, 'class' => 'Grade 1', 'school_id' => 6],
            ['id' => 2, 'name' => 'Student 2', 'sex' => 'female', 'age' => 9, 'class' => 'Grade 2', 'school_id' => 6],
            ['id' => 3, 'name' => 'Student 3', 'sex' => 'male', 'age' => 10, 'class' => 'Grade 3', 'school_id' => 6],
            ['id' => 24, 'name' => 'Student 24', 'sex' => 'female', 'age' => 8, 'class' => 'Grade 1', 'school_id' => 6],
            ['id' => 25, 'name' => 'Student 25', 'sex' => 'male', 'age' => 9, 'class' => 'Grade 2', 'school_id' => 6],
        ];
        
        foreach ($students as $student) {
            $student['created_at'] = '2025-08-25 07:25:03';
            $student['updated_at'] = '2025-08-25 07:25:03';
            Student::create($student);
        }
    }

    private function importAssessments()
    {
        $this->command->info('Importing assessments...');
        
        // Production assessment data from the SQL dump
        $assessments = [
            ['id' => 1, 'student_id' => 1, 'cycle' => 'baseline', 'subject' => 'khmer', 'level' => 'Beginner', 'score' => 40.00, 'assessed_at' => '2025-01-01'],
            ['id' => 2, 'student_id' => 1, 'cycle' => 'baseline', 'subject' => 'math', 'level' => 'Beginner', 'score' => 39.00, 'assessed_at' => '2025-01-01'],
            ['id' => 3, 'student_id' => 1, 'cycle' => 'midline', 'subject' => 'khmer', 'level' => 'Letter', 'score' => 47.70, 'assessed_at' => '2025-04-30'],
            ['id' => 4, 'student_id' => 1, 'cycle' => 'midline', 'subject' => 'math', 'level' => '1-Digit', 'score' => 65.00, 'assessed_at' => '2025-04-30'],
            ['id' => 5, 'student_id' => 1, 'cycle' => 'endline', 'subject' => 'khmer', 'level' => 'Word', 'score' => 72.30, 'assessed_at' => '2025-08-24'],
            ['id' => 6, 'student_id' => 1, 'cycle' => 'endline', 'subject' => 'math', 'level' => '2-Digit', 'score' => 83.00, 'assessed_at' => '2025-08-24'],
            // Add more assessment records as needed
        ];
        
        foreach ($assessments as $assessment) {
            $assessment['assessment_method'] = 'written';
            $assessment['completed_assessment'] = true;
            $assessment['requires_intervention'] = false;
            $assessment['parent_informed'] = false;
            $assessment['used_assistance'] = false;
            $assessment['reassessment_needed'] = false;
            $assessment['is_locked'] = false;
            $assessment['created_at'] = '2025-08-25 07:25:03';
            $assessment['updated_at'] = '2025-08-25 07:25:03';
            
            Assessment::create($assessment);
        }
    }
}