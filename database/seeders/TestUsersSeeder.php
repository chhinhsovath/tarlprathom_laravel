<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $testUsers = [
            [
                'name' => 'Coordinator User',
                'email' => 'coordinator@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'coordinator',
                'is_active' => true,
            ],
            [
                'name' => 'Kairav Admin',
                'email' => 'kairav@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Mentor One',
                'email' => 'mentor1@prathaminternational.org',
                'password' => Hash::make('admin123'),
                'role' => 'mentor',
                'is_active' => true,
            ],
            [
                'name' => 'Teacher One',
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

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        echo "Test users created successfully!\n";
    }
}
