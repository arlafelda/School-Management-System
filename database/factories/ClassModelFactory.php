<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->randomElement(['10', '11', '12']);
        $major = fake()->randomElement(['IPA', 'IPS', 'Bahasa']);
        $number = fake()->unique()->numberBetween(1, 9);

        $name = "Kelas {$level} {$major} {$number}";

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'archived' => 0,
            'level' => $level,
            'major' => $major,
            'academic_year' => '2025/2026',
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            // Nullable: wali kelas bisa ditentukan belakangan.
            'teacher_id' => Teacher::factory(),
        ];
    }

    /**
     * Indicate the class has no homeroom teacher assigned yet.
     */
    public function withoutTeacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'teacher_id' => null,
        ]);
    }

    /**
     * Indicate the class is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}
