<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Student;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $schools = School::all();
        $classes = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];

        // Khmer names for more realistic data
        $khmerFirstNames = ['Sok', 'Chan', 'Srey', 'Vanna', 'Dara', 'Sophea', 'Kosal', 'Pisey', 'Bopha', 'Samnang', 'Kunthea', 'Visal', 'Rachana', 'Sovann', 'Phalla'];
        $khmerLastNames = ['Lim', 'Chea', 'Heng', 'Pich', 'Sam', 'Keo', 'Mao', 'Seng', 'Ung', 'Ouk', 'Chhun', 'Touch', 'Sor', 'Ly', 'Kim'];

        foreach ($schools as $school) {
            // Create 20 students per school (100 total)
            for ($i = 0; $i < 20; $i++) {
                Student::create([
                    'name' => $faker->randomElement($khmerFirstNames).' '.$faker->randomElement($khmerLastNames),
                    'sex' => $faker->randomElement(['male', 'female']),
                    'age' => $faker->numberBetween(6, 12),
                    'class' => $faker->randomElement($classes),
                    'school_id' => $school->id,
                ]);
            }
        }
    }
}
