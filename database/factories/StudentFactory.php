<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->name();
        $gender = fake()->randomElement(['L', 'P']);

        return [
            // Creates a brand new User with role student by default.
            'user_id' => User::factory()->student(),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'nisn' => fake()->unique()->numerify('00##########'),
            'nis' => fake()->unique()->numerify('########'),
            'name' => $name,
            'gender' => $gender,
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'address' => fake()->address(),
            'phone' => fake()->numerify('08##########'),
            'class_id' => ClassModel::factory(),
            'major' => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'status' => 'aktif',
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'parent_phone' => fake()->numerify('08##########'),
            'parent_address' => fake()->address(),
            'archived' => 0,
        ];
    }

    /**
     * Indicate the student has graduated.
     */
    public function lulus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lulus',
        ]);
    }

    /**
     * Indicate the student has transferred out.
     */
    public function pindah(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pindah',
        ]);
    }

    /**
     * Indicate the student is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}
