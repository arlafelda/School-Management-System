<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->randomElement(['07:00', '08:30', '10:00', '12:30', '14:00']);
        $end = date('H:i', strtotime($start . ' +90 minutes'));

        return [
            'class_id' => ClassModel::factory(),
            'subject_id' => Subject::factory(),
            'day' => fake()->randomElement([
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
            ]),
            'start_time' => $start,
            'end_time' => $end,
            'archived' => 0,
            'teacher_id' => Teacher::factory(),
        ];
    }

    /**
     * Indicate the schedule is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived' => 1,
        ]);
    }
}
