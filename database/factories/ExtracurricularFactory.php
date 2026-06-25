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
            'name'       => $name,
            'slug'       => Str::slug($name),
            'teacher_id' => Teacher::factory(),
        ];
    }

    /**
     * Ekstrakurikuler tanpa pembina.
     */
    public function withoutTeacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'teacher_id' => null,
        ]);
    }

    /**
     * Simulate a soft-deleted (trashed) extracurricular.
     */
    public function trashed(): static
    {
        return $this->afterCreating(function ($extracurricular) {
            $extracurricular->delete();
        });
    }
}
