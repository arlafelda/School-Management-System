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

        return [
            'user_id'        => User::factory()->student(),
            'slug'           => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'nisn'           => fake()->unique()->numerify('00##########'),
            'nis'            => fake()->unique()->numerify('########'),
            'name'           => $name,
            'gender'         => fake()->randomElement(['L', 'P']),
            'birth_place'    => fake()->city(),
            'birth_date'     => fake()->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'address'        => fake()->address(),
            'phone'          => fake()->numerify('08##########'),
            'class_id'       => ClassModel::factory(),
            'major'          => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'status'         => 'aktif',
            'father_name'    => fake()->name('male'),
            'mother_name'    => fake()->name('female'),
            'parent_phone'   => fake()->numerify('08##########'),
            'parent_address' => fake()->address(),
        ];
    }

    /**
     * Siswa dengan status lulus.
     */
    public function lulus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lulus',
        ]);
    }

    /**
     * Siswa dengan status pindah.
     */
    public function pindah(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pindah',
        ]);
    }

    /**
     * Simulate a soft-deleted (trashed) student.
     * Juga men-trash user terkait agar konsisten.
     */
    public function trashed(): static
    {
        return $this->afterCreating(function ($student) {
            $student->user?->delete();
            $student->delete();
        });
    }
}
