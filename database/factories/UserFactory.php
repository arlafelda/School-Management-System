<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->unique()->name()) . '-' . fake()->unique()->numberBetween(1, 9999),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => 'student',
            'archived' => 0,
            'creation_time' => now(),
            'create_id' => 1,
            'update_time' => now(),
            'update_id' => 1,
        ];
    }
}