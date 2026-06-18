<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => ClassModel::inRandomOrder()->first()->id ?? 1,
            'subject_id' => Subject::inRandomOrder()->first()->id ?? 1,
            'teacher_id' => User::where('role', 'teacher')->inRandomOrder()->first()->id ?? 1,

            'day' => fake()->randomElement([
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday'
            ]),

            'start_time' => fake()->time('H:i', '07:00'),
            'end_time' => fake()->time('H:i', '15:00'),
        ];
    }
}