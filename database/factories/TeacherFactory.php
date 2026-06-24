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
            'user_id' => User::factory()->teacher(),
            'nip' => fake()->unique()->numerify('19#########0##1'),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'position' => 'Guru', // default, di-override di seeder kalau perlu jadi Wali Kelas
            'phone' => fake()->numerify('08##########'),
            'address' => fake()->address(),
            'archived' => 0,
        ];
    }

    /**
     * Indicate the teacher is a homeroom teacher (Wali Kelas).
     */
    public function waliKelas(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'Wali Kelas',
        ]);
    }

    /**
     * Indicate the teacher is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}