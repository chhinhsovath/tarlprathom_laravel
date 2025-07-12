<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition()
    {
        $provinces = ['Phnom Penh', 'Siem Reap', 'Battambang', 'Kampong Cham', 'Kandal'];

        return [
            'school_name' => $this->faker->company().' Primary School',
            'address' => $this->faker->streetAddress().', '.$this->faker->randomElement($provinces),
        ];
    }
}
