<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DefaultUsersSeeder::class,  // Create default users first
            PilotSchoolsSeeder::class,  // Import pilot schools from Excel
            SchoolSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            ComprehensiveAssessmentSeeder::class,
            EnhancedTranslationsSeeder::class,
            LearningOutcomesSeeder::class,
            InterventionProgramsSeeder::class,
            TeachingActivitiesSeeder::class,
        ]);
    }
}
