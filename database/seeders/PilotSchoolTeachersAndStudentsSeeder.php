<?php

namespace Database\Seeders;

use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotSchoolTeachersAndStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pilotSchools = PilotSchool::all();
        
        if ($pilotSchools->isEmpty()) {
            $this->command->warn('No pilot schools found. Please run the pilot schools seeder first.');
            return;
        }

        $this->command->info("Seeding teachers and students for {$pilotSchools->count()} pilot schools...");

        foreach ($pilotSchools as $index => $school) {
            $this->command->info("Processing school: {$school->school_name}");

            // Get or create 2-3 teachers per school
            $existingTeachers = User::where('pilot_school_id', $school->id)
                ->where('role', 'teacher')
                ->get();
            
            $teachers = $existingTeachers->all();
            $teacherCount = rand(2, 3);
            
            if ($existingTeachers->count() < $teacherCount) {
                $teachersToCreate = $teacherCount - $existingTeachers->count();
                
                for ($i = $existingTeachers->count() + 1; $i <= $teacherCount; $i++) {
                    $teacher = User::create([
                        'name' => "Teacher {$i} - {$school->school_name}",
                        'email' => "teacher{$i}.school{$school->id}@tarlprathom.edu.kh",
                        'email_verified_at' => now(),
                        'password' => bcrypt('password123'),
                        'role' => 'teacher',
                        'pilot_school_id' => $school->id,
                        'is_active' => true,
                    ]);
                    $teachers[] = $teacher;
                }
                
                $this->command->info("  Created {$teachersToCreate} new teachers (total: {$teacherCount})");
            } else {
                $this->command->info("  School already has {$existingTeachers->count()} teachers");
            }

            // Ensure proper student distribution per grade (30-45 students each)
            $grades = ['Grade 4', 'Grade 5', 'Grade 6'];
            
            foreach ($grades as $grade) {
                // Check current student count for this grade in this school
                $currentCount = Student::where('pilot_school_id', $school->id)
                    ->where('class', $grade)
                    ->count();

                $targetCount = rand(30, 45);
                
                if ($currentCount < $targetCount) {
                    $studentsToCreate = $targetCount - $currentCount;
                    
                    for ($i = 1; $i <= $studentsToCreate; $i++) {
                        $randomTeacher = $teachers[array_rand($teachers)];
                        
                        $isMale = rand(0, 1);
                        Student::create([
                            'name' => $this->generateKhmerName() . ' ' . $i,
                            'sex' => $isMale ? 'male' : 'female',
                            'gender' => $isMale ? 'male' : 'female',
                            'age' => 8 + (int)substr($grade, -1), // Age based on grade
                            'class' => $grade,
                            'school_id' => 1, // Default school_id for legacy compatibility
                            'pilot_school_id' => $school->id,
                            'teacher_id' => $randomTeacher->id,
                            'date_of_birth' => now()->subYears(8 + (int)substr($grade, -1))->subDays(rand(0, 365)),
                            'enrollment_status' => 'active',
                            'enrollment_date' => now()->subDays(rand(30, 365)),
                        ]);
                    }
                    
                    $this->command->info("  Created {$studentsToCreate} students for {$grade} (total: {$targetCount})");
                } else {
                    $this->command->info("  {$grade} already has {$currentCount} students (target: {$targetCount})");
                }
            }
        }

        $this->command->info('Pilot school teachers and students seeding completed!');
    }

    /**
     * Generate a random Khmer name
     */
    private function generateKhmerName(): string
    {
        $khmerFirstNames = [
            'សុខា', 'ចន្ទា', 'សុផាត', 'ពិសាខ', 'ច័ន្ទ', 'សុម', 'រតនា', 'គន្ធា', 'ម៉ាលី', 'ប៊ុនធឿន',
            'សុវណ្ណ', 'កញ្ញា', 'ភក្តី', 'សុភាព', 'ច័ន្ទនី', 'សុខុម', 'សុវណ្ណី', 'ពេជ្រ', 'មករា', 'កុសល',
            'ស្រីពេជ្រ', 'សុភ័ក្ត្រ', 'ចាន់ធី', 'សោម៉ា', 'ពិទូ', 'សុខេន', 'រស្មី', 'ច័ន្ទរស្មី', 'បុ៊នលី', 'ស្រីមុំ'
        ];

        $khmerLastNames = [
            'លី', 'យ៉ាន', 'ឈុន', 'ច័ន្ទ', 'ចាន់', 'គឹម', 'សុខ', 'ភួង', 'ឌឿន', 'ម៉េង',
            'សុវណ្ណ', 'ធី', 'គង់', 'សាន', 'វិន', 'ហុក', 'លឹម', 'សេង', 'រស់', 'ថុល'
        ];

        $firstName = $khmerFirstNames[array_rand($khmerFirstNames)];
        $lastName = $khmerLastNames[array_rand($khmerLastNames)];

        return $firstName . ' ' . $lastName;
    }
}