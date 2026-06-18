<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClassModelFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->randomElement(['X', 'XI', 'XII']);

        $major = fake()->randomElement(['IPA', 'IPS']);

        $number = fake()->unique()->numberBetween(1, 9);

        $name = $level . ' ' . $major . ' ' . $number;

        return [
            'name' => $name,
            'level' => $level,
            'teacher_id' => null,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}