<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UpdateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating/Creating user accounts...');

        // Get a school for teacher and mentor assignments
        $school = School::first();
        
        // 1. Coordinator Account
        User::updateOrCreate(
            ['email' => 'coordinator@prathaminternational.org'],
            [
                'name' => 'Coordinator User',
                'password' => Hash::make('admin123'),
                'role' => 'coordinator',
                'is_active' => true,
                'sex' => 'M',
                'phone' => '012345679',
            ]
        );
        $this->command->info('✓ Coordinator account created/updated');

        // 2. Admin Account
        User::updateOrCreate(
            ['email' => 'kairav@prathaminternational.org'],
            [
                'name' => 'Kairav Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'sex' => 'M',
                'phone' => '012345678',
            ]
        );
        $this->command->info('✓ Admin account created/updated');

        // 3. Mentor Account
        $mentor = User::updateOrCreate(
            ['email' => 'mentor1@prathaminternational.org'],
            [
                'name' => 'Mentor One',
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
                'sex' => 'F',
                'phone' => '012345680',
            ]
        );
        
        // Assign schools to mentor (assign first 10 schools)
        if ($mentor) {
            $schoolIds = School::take(10)->pluck('id')->toArray();
            $mentor->assignedSchools()->sync($schoolIds);
            $this->command->info('✓ Mentor account created/updated and assigned to 10 schools');
        }

        // 4. Teacher Account
        User::updateOrCreate(
            ['email' => 'teacher1@prathaminternational.org'],
            [
                'name' => 'Teacher One',
                'password' => Hash::make('admin123'),
                'role' => 'teacher',
                'school_id' => $school ? $school->id : null,
                'is_active' => true,
                'sex' => 'F',
                'phone' => '012345681',
                'holding_classes' => 'Grade 1, Grade 2',
            ]
        );
        $this->command->info('✓ Teacher account created/updated');

        // 5. Viewer Account
        User::updateOrCreate(
            ['email' => 'viewer@prathaminternational.org'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('admin123'),
                'role' => 'viewer',
                'is_active' => true,
                'sex' => 'M',
                'phone' => '012345682',
            ]
        );
        $this->command->info('✓ Viewer account created/updated');

        $this->command->info('');
        $this->command->info('All user accounts have been created/updated successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('=================');
        $this->command->info('Coordinator: coordinator@prathaminternational.org / admin123');
        $this->command->info('Admin: kairav@prathaminternational.org / admin123');
        $this->command->info('Mentor: mentor1@prathaminternational.org / admin123');
        $this->command->info('Teacher: teacher1@prathaminternational.org / admin123');
        $this->command->info('Viewer: viewer@prathaminternational.org / admin123');
    }
}