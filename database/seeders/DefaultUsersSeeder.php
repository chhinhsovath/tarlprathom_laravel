<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultUsers = [
            [
                'name' => 'Coordinator User',
                'email' => 'coordinator@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'coordinator',
                'is_active' => true,
            ],
            [
                'name' => 'Admin User',
                'email' => 'kairav@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Mentor User',
                'email' => 'mentor1@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
            ],
            [
                'name' => 'Teacher User',
                'email' => 'teacher1@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'teacher',
                'is_active' => true,
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'viewer',
                'is_active' => true,
            ],
        ];

        foreach ($defaultUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Default users created successfully.');
    }
}