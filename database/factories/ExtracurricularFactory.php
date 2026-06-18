<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExtracurricularFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Pramuka',
                'Paskibra',
                'Futsal',
                'Basket',
                'Volly',
                'PMR',
                'OSIS',
                'Seni Tari',
                'Seni Musik',
                'Robotik',
            ]),

            'coach_name' => fake()->name(),

            'description' => fake()->sentence(10),
        ];
    }
}