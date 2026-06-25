<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'user_id'  => User::factory()->teacher(),
            'nip'      => fake()->unique()->numerify('19#########0##1'),
            'name'     => $name,
            'slug'     => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'position' => 'Guru',
            'phone'    => fake()->numerify('08##########'),
            'address'  => fake()->address(),
        ];
    }

    /**
     * Guru dengan posisi Wali Kelas.
     */
    public function waliKelas(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'Wali Kelas',
        ]);
    }

    /**
     * Simulate a soft-deleted (trashed) teacher.
     * Juga men-trash user terkait agar konsisten.
     */
    public function trashed(): static
    {
        return $this->afterCreating(function ($teacher) {
            $teacher->user?->delete();
            $teacher->delete();
        });
    }
}
