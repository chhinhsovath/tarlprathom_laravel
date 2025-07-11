<?php

namespace Database\Factories;

use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MentoringVisitFactory extends Factory
{
    protected $model = MentoringVisit::class;

    public function definition()
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);
        $mentor = User::factory()->mentor()->create();
        
        return [
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
            'mentor_id' => $mentor->id,
            'visit_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'score' => $this->faker->numberBetween(60, 100),
            'observation' => $this->faker->paragraph(3),
            'action_plan' => $this->faker->paragraph(2),
            'follow_up_required' => $this->faker->boolean(30), // 30% chance of follow-up required
            'photo' => null,
        ];
    }

    public function withPhoto()
    {
        return $this->state(function (array $attributes) {
            return [
                'photo' => 'mentoring-visits/' . $this->faker->uuid() . '.jpg',
            ];
        });
    }

    public function requiresFollowUp()
    {
        return $this->state(function (array $attributes) {
            return [
                'follow_up_required' => true,
                'score' => $this->faker->numberBetween(30, 59), // Lower score for follow-up
            ];
        });
    }
}