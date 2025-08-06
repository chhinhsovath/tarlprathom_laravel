<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TarlDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create 2 schools
        $schools = [];
        for ($i = 1; $i <= 2; $i++) {
            $schools[] = School::create([
                'name' => "Demo School $i",
                'address' => "123 Main Street, District $i",
                'contact_person' => "Principal $i",
                'phone' => "0812345678$i",
                'email' => "school$i@demo.com",
                'province' => 'Bangkok',
                'district' => "District $i",
                'subdistrict' => "Subdistrict $i",
                'postal_code' => "1000$i",
                'school_code' => "SCH00$i",
                'education_service_area' => "Area $i",
                'status' => 'active',
            ]);
        }

        // Create 1 mentor that engages with all schools
        $mentor = User::create([
            'name' => 'Demo Mentor',
            'email' => 'mentor@demo.com',
            'password' => Hash::make('password'),
            'role' => 'mentor',
            'remember_token' => Str::random(10),
        ]);

        // Attach mentor to both schools using assignedSchools relationship
        $schoolIds = collect($schools)->pluck('id')->toArray();
        $mentor->assignedSchools()->attach($schoolIds);

        // Create 4 teachers (2 per school)
        $teachers = [];
        foreach ($schools as $index => $school) {
            for ($t = 1; $t <= 2; $t++) {
                $teacherNumber = ($index * 2) + $t;
                $teacher = User::create([
                    'name' => "Teacher $teacherNumber",
                    'email' => "teacher$teacherNumber@demo.com",
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'school_id' => $school->id,
                    'remember_token' => Str::random(10),
                ]);
                $teachers[] = ['teacher' => $teacher, 'school' => $school];
            }
        }

        // Create 100 students per school (50 grade 4, 50 grade 5)
        $studentCount = 0;
        foreach ($schools as $schoolIndex => $school) {
            for ($grade = 4; $grade <= 5; $grade++) {
                for ($s = 1; $s <= 50; $s++) {
                    $studentCount++;
                    Student::create([
                        'school_id' => $school->id,
                        'student_code' => sprintf('STU%04d', $studentCount),
                        'first_name' => 'Student',
                        'last_name' => "Number$studentCount",
                        'nickname' => "S$studentCount",
                        'birthdate' => now()->subYears(9 + $grade - 4),
                        'gender' => $s % 2 == 0 ? 'male' : 'female',
                        'grade' => $grade,
                        'section' => ceil($s / 25), // 2 sections per grade
                        'student_number' => $s,
                        'status' => 'active',
                        'parent_name' => "Parent of Student $studentCount",
                        'parent_phone' => sprintf('08%08d', $studentCount),
                        'address' => "House $studentCount, Street $grade",
                        'subdistrict' => $school->subdistrict,
                        'district' => $school->district,
                        'province' => $school->province,
                        'postal_code' => $school->postal_code,
                    ]);
                }
            }
        }

        // Assign students to teachers (25 students per teacher)
        $allStudents = Student::all();
        $studentsPerTeacher = 25;

        foreach ($teachers as $index => $teacherData) {
            $startIndex = $index * $studentsPerTeacher;
            $assignedStudents = $allStudents->slice($startIndex, $studentsPerTeacher);

            foreach ($assignedStudents as $student) {
                $student->teacher_id = $teacherData['teacher']->id;
                $student->save();
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 2 schools');
        $this->command->info('- 1 mentor (engaging with both schools)');
        $this->command->info('- 4 teachers (2 per school, each handling 25 students)');
        $this->command->info('- 200 students (100 per school, 50 per grade)');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Mentor: mentor@demo.com / password');
        $this->command->info('Teachers: teacher1@demo.com to teacher4@demo.com / password');
    }
}
