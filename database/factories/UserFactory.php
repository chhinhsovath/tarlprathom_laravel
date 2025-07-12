<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'viewer',
            'is_active' => true,
            'school_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'school_id' => null,
        ]);
    }

    /**
     * Indicate that the user is a teacher.
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'teacher',
            'school_id' => School::factory(),
        ]);
    }

    /**
     * Indicate that the user is a mentor.
     */
    public function mentor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'mentor',
            'school_id' => null,
        ]);
    }

    /**
     * Indicate that the user is a viewer.
     */
    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'viewer',
            'school_id' => null,
        ]);
    }
}
