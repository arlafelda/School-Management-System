<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'Informatika',
        ]);

        return [
            'name' => $name,
            'code' => strtoupper(fake()->unique()->lexify('SUB???')),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}