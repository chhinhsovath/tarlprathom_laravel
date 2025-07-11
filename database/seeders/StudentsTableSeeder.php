<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\School;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first school or create one
        $school = School::first();
        if (!$school) {
            $school = School::create([
                'name' => 'Primary School 1',
                'district' => 'District 1',
                'province' => 'Province 1'
            ]);
        }
        
        // Sample Khmer names for students
        $students = [
            ['name' => 'សុភា', 'gender' => 'male'],
            ['name' => 'សុខា', 'gender' => 'female'],
            ['name' => 'ចាន់ធី', 'gender' => 'male'],
            ['name' => 'សុភាព', 'gender' => 'female'],
            ['name' => 'វិសាល', 'gender' => 'male'],
            ['name' => 'សោភា', 'gender' => 'female'],
            ['name' => 'ពិសិដ្ឋ', 'gender' => 'male'],
            ['name' => 'មាលា', 'gender' => 'female'],
            ['name' => 'សុវណ្ណ', 'gender' => 'male'],
            ['name' => 'រតនា', 'gender' => 'female'],
            ['name' => 'សម្បត្តិ', 'gender' => 'male'],
            ['name' => 'សុជាតា', 'gender' => 'female'],
            ['name' => 'វិជ្ជា', 'gender' => 'male'],
            ['name' => 'ចន្ទ្រា', 'gender' => 'female'],
            ['name' => 'សុរិយា', 'gender' => 'male'],
        ];
        
        foreach ($students as $studentData) {
            Student::create([
                'name' => $studentData['name'],
                'school_id' => $school->id,
                'sex' => $studentData['gender'],
                'gender' => $studentData['gender'],
                'age' => rand(8, 12),
                'class' => '3A'
            ]);
        }
    }
}