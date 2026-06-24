<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Extracurricular>
 */
class ExtracurricularFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Pramuka',
            'Paskibra',
            'Basket',
            'Futsal',
            'Voli',
            'Pencak Silat',
            'Karate',
            'Paduan Suara',
            'Tari Tradisional',
            'Robotika',
            'English Club',
            'Karya Ilmiah Remaja',
            'Palang Merah Remaja',
            'Jurnalistik',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'archived' => 0,
            'teacher_id' => Teacher::factory(),
        ];
    }

    /**
     * Indicate the extracurricular has no supervising teacher yet.
     */
    public function withoutTeacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'teacher_id' => null,
        ]);
    }

    /**
     * Indicate the extracurricular is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}
