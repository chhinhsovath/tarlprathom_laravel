<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'grade' => $this->faker->numberBetween(1, 6),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-12 years', '-6 years'),
            'school_id' => School::factory(),
            'address' => $this->faker->address(),
            'parent_name' => $this->faker->name(),
            'contact_number' => $this->faker->phoneNumber(),
        ];
    }

    public function male()
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'male',
                'name' => $this->faker->name('male'),
            ];
        });
    }

    public function female()
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'female',
                'name' => $this->faker->name('female'),
            ];
        });
    }

    public function grade($grade)
    {
        return $this->state(function (array $attributes) use ($grade) {
            return [
                'grade' => $grade,
            ];
        });
    }
}
