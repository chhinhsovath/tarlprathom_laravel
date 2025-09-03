<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\PilotSchool;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Starting students seeding process...");

        // Clear existing students data and related records
        $this->command->info("Clearing existing students and related data...");
        
        // Clear related records first
        \DB::table('assessments')->delete();
        \DB::table('student_assessment_eligibility')->delete();
        \DB::table('assessment_histories')->delete();
        
        // Check for other related tables and clear them
        $relatedTables = [
            'attendance_records',
            'student_learning_outcomes', 
            'student_interventions',
            'progress_tracking'
        ];

        foreach ($relatedTables as $table) {
            if (\Schema::hasTable($table)) {
                \DB::table($table)->delete();
                $this->command->info("Cleared {$table} table");
            }
        }
        
        // Now clear students
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Student::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all pilot schools
        $pilotSchools = PilotSchool::all();
        $this->command->info("Found {$pilotSchools->count()} pilot schools.");
        
        // Get a default school_id for foreign key constraint
        $defaultSchoolId = \DB::table('schools')->first()->id ?? 1;

        $faker = Faker::create('km_KH'); // Khmer locale
        $englishFaker = Faker::create('en_US'); // English fallback

        // Common Khmer names
        $khmerMaleNames = [
            'សុខា', 'ដារា', 'សុភាព', 'រតនា', 'វិសាល', 'កុសល', 'សុវណ្ណ', 'ពិសី', 
            'ចន្ទា', 'រាជា', 'មករា', 'វីរៈ', 'ពូជា', 'ស្រីនាថ', 'ម៉េងហេង',
            'សុខុម', 'ប៉ូលីន', 'រិទ្ធី', 'ចំនាន់', 'វុទ្ធី', 'សុភ័ក្តិ', 'មុន្នី'
        ];

        $khmerFemaleNames = [
            'សុភាព', 'ប៊ុបផា', 'រស្មី', 'ចន្ទនី', 'គន្ធា', 'វណ្ណា', 'ស្រីមាន', 'រី',
            'សុវត្ថិ', 'ពេជ្រ', 'សោភា', 'រតនា', 'សុធារី', 'កុលាប', 'ម្លិះ',
            'វិជ្ជា', 'សុខវតី', 'រុទ្ធី', 'កញ្ញា', 'រីកា', 'សុគន្ធ', 'និម្មល'
        ];

        $khmerSurnames = [
            'ស៊ុក', 'លី', 'ចាន់', 'កែវ', 'ហេង', 'អេង', 'សុខ', 'ម៉ៅ', 'ហុក',
            'ពិជ', 'ឈុន', 'ឃុន', 'សេង', 'អ៊ុក', 'លឹម', 'ឃឹម', 'ពុជ', 'រស',
            'សុង', 'រិន', 'រុន', 'យន', 'យាយ', 'ពេង', 'ហុង', 'ភុក'
        ];

        $grades = [1, 2, 3, 4, 5, 6]; // Numeric grades 1-6
        $classes = ['A', 'B', 'C', 'D']; // Class sections

        $totalStudents = 0;

        foreach ($pilotSchools as $school) {
            // Random number between 30-35 students per school
            $numStudents = rand(30, 35);
            $this->command->info("Creating {$numStudents} students for {$school->school_name}...");

            // Track used names in each class for this school to ensure uniqueness
            $usedNamesPerClass = [];

            for ($i = 0; $i < $numStudents; $i++) {
                $sex = $faker->randomElement(['male', 'female']);
                $isMale = ($sex === 'male');
                
                $grade = $faker->randomElement($grades);
                $classSection = $faker->randomElement($classes);
                $classKey = $grade . $classSection;
                
                // Generate unique Khmer name for this class
                $attempts = 0;
                do {
                    $firstName = $isMale 
                        ? $faker->randomElement($khmerMaleNames)
                        : $faker->randomElement($khmerFemaleNames);
                    $lastName = $faker->randomElement($khmerSurnames);
                    $fullName = $firstName . ' ' . $lastName;
                    $attempts++;
                    
                    // Add a number suffix if name collision after many attempts
                    if ($attempts > 20) {
                        $fullName = $firstName . ' ' . $lastName . ' ' . ($i + 1);
                    }
                } while (
                    isset($usedNamesPerClass[$classKey]) && 
                    in_array($fullName, $usedNamesPerClass[$classKey]) && 
                    $attempts < 30
                );
                
                // Track this name for this class
                if (!isset($usedNamesPerClass[$classKey])) {
                    $usedNamesPerClass[$classKey] = [];
                }
                $usedNamesPerClass[$classKey][] = $fullName;
                
                $age = rand(6, 12);

                Student::create([
                    'name' => $fullName,
                    'sex' => $sex,
                    'gender' => $sex,
                    'age' => $age,
                    'class' => $classKey, // e.g., "1A"
                    'school_id' => $defaultSchoolId, // For FK constraint (legacy)
                    'pilot_school_id' => $school->id, // Primary pilot school relationship
                    'student_code' => $school->school_code . sprintf('%03d', $i + 1),
                    'date_of_birth' => now()->subYears($age)->subDays(rand(0, 365)),
                    'nationality' => 'ខ្មែរ',
                    'ethnicity' => 'ខ្មែរ',
                    'religion' => $faker->randomElement(['ព្រះពុទ្ធសាសនា', 'គ្រឹស្តសាសនា', 'អ៊ីស្លាមសាសនា', null]),
                    'guardian_name' => $faker->randomElement($khmerMaleNames) . ' ' . $faker->randomElement($khmerSurnames),
                    'guardian_relationship' => $faker->randomElement(['ឪពុក', 'ម្តាយ', 'តា', 'យាយ', 'ពូ', 'មីង']),
                    'guardian_phone' => '0' . rand(10, 99) . rand(100000, 999999),
                    'guardian_occupation' => $faker->randomElement(['កសិករ', 'អាជីវករ', 'គ្រូបង្រៀន', 'ពេទ្យ', 'កម្មករ', 'អំណាចរដ្ឋ']),
                    'home_address' => 'ផ្ទះលេខ ' . rand(1, 999),
                    'home_village' => 'ភូមិ' . $faker->randomElement(['សាមគ្គី', 'ប្រាស្រ័យ', 'ស្វាយរៀង', 'ពោធិ៍ពេជ្រ']),
                    'home_commune' => 'ឃុំ/សង្កាត់ ' . $school->cluster,
                    'home_district' => $school->district,
                    'home_province' => $school->province,
                    'distance_from_school' => round(rand(1, 50) / 10, 1), // 0.1 to 5.0 km
                    'transportation_method' => $faker->randomElement(['walk', 'bicycle', 'motorcycle', 'car']),
                    'has_disability' => $faker->boolean(10), // 10% chance
                    'disability_type' => $faker->boolean(10) ? $faker->randomElement(['ពិការភ្នែក', 'ពិការត្រចៀក', 'ពិការជើង', 'ពិការដៃ']) : null,
                    'receives_scholarship' => $faker->boolean(20), // 20% chance
                    'scholarship_amount' => $faker->boolean(20) ? rand(50, 200) : null,
                    'enrollment_date' => now()->subMonths(rand(1, 12)),
                    'enrollment_status' => $faker->randomElement(['active', 'active', 'active', 'dropped_out']), // 75% active
                    'previous_year_grade' => $grade === 1 ? null : $grade - 1,
                    'attendance_rate' => rand(75, 100),
                    'days_absent' => rand(0, 30),
                    'height_cm' => rand(90, 150),
                    'weight_kg' => rand(15, 45),
                    'nutrition_status' => $faker->randomElement(['normal', 'underweight', 'overweight', 'malnourished']),
                    'receives_meal_support' => $faker->boolean(30), // 30% chance
                    'siblings_count' => rand(0, 5),
                    'sibling_position' => rand(1, 6),
                    'family_income_level' => $faker->randomElement(['low', 'medium', 'high']),
                    'has_birth_certificate' => $faker->boolean(80), // 80% have birth certificate
                    'birth_certificate_number' => $faker->boolean(80) ? $faker->numerify('########') : null,
                ]);

                $totalStudents++;
            }
        }

        $this->command->info("Successfully created {$totalStudents} students across {$pilotSchools->count()} pilot schools.");
        
        // Show distribution
        $this->command->info("Student distribution by pilot school:");
        $distribution = Student::selectRaw('pilot_school_id, COUNT(*) as count')
            ->groupBy('pilot_school_id')
            ->with('pilotSchool:id,school_name')
            ->get();

        foreach ($distribution->take(5) as $item) {
            $schoolName = $item->pilotSchool ? $item->pilotSchool->school_name : 'Unknown';
            $this->command->line("- {$schoolName}: {$item->count} students");
        }

        if ($distribution->count() > 5) {
            $this->command->line("... and " . ($distribution->count() - 5) . " more schools");
        }
    }
}