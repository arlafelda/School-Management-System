<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Ekonomi',
            'Sosiologi',
            'Pendidikan Agama',
            'Pendidikan Jasmani',
            'Seni Budaya',
            'Informatika',
            'Pendidikan Kewarganegaraan',
        ]);

        return [
            'name' => $name,
            'code' => strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)) . fake()->unique()->numberBetween(100, 999),
            'kkm' => 75,
            'description' => fake()->sentence(12),
            'slug' => Str::slug($name),
            'archived' => 0,
        ];
    }

    /**
     * Indicate the subject is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}
