<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        $subject = $this->faker->randomElement(['khmer', 'math']);

        if ($subject === 'khmer') {
            $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        } else {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
        }

        return [
            'student_id' => Student::factory(),
            'subject' => $subject,
            'cycle' => $this->faker->randomElement(['baseline', 'midline', 'endline']),
            'level' => $this->faker->randomElement($levels),
            'score' => $this->faker->numberBetween(0, 100),
            'assessed_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function khmer()
    {
        return $this->state(function (array $attributes) {
            $levels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];

            return [
                'subject' => 'khmer',
                'level' => $this->faker->randomElement($levels),
            ];
        });
    }

    public function math()
    {
        return $this->state(function (array $attributes) {
            $levels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];

            return [
                'subject' => 'math',
                'level' => $this->faker->randomElement($levels),
            ];
        });
    }

    public function baseline()
    {
        return $this->state(function (array $attributes) {
            return [
                'cycle' => 'baseline',
            ];
        });
    }

    public function midline()
    {
        return $this->state(function (array $attributes) {
            return [
                'cycle' => 'midline',
            ];
        });
    }

    public function endline()
    {
        return $this->state(function (array $attributes) {
            return [
                'cycle' => 'endline',
            ];
        });
    }
}
