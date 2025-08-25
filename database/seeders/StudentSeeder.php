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
        // Get schools from the schools table, not from School model which uses tbl_tarl_schools
        $schoolIds = \DB::table('schools')->pluck('id')->toArray();

        if (empty($schoolIds)) {
            $this->command->info('No schools found in schools table.');

            return;
        }

        $classes = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];

        // Khmer names for more realistic data
        $khmerFirstNames = ['Sok', 'Chan', 'Srey', 'Vanna', 'Dara', 'Sophea', 'Kosal', 'Pisey', 'Bopha', 'Samnang', 'Kunthea', 'Visal', 'Rachana', 'Sovann', 'Phalla'];
        $khmerLastNames = ['Lim', 'Chea', 'Heng', 'Pich', 'Sam', 'Keo', 'Mao', 'Seng', 'Ung', 'Ouk', 'Chhun', 'Touch', 'Sor', 'Ly', 'Kim'];

        // Create 100 students distributed among schools
        for ($i = 0; $i < 100; $i++) {
            Student::firstOrCreate(
                [
                    'name' => $faker->randomElement($khmerFirstNames).' '.$faker->randomElement($khmerLastNames),
                    'school_id' => $faker->randomElement($schoolIds),
                ],
                [
                    'sex' => $faker->randomElement(['male', 'female']),
                    'age' => $faker->numberBetween(6, 12),
                    'class' => $faker->randomElement($classes),
                ]
            );
        }
    }
}
