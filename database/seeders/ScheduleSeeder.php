<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::pluck('id')->toArray();
        $classes = ClassModel::pluck('id')->toArray();
        $subjects = Subject::pluck('id')->toArray();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        for ($i = 0; $i < 10; $i++) {
            Schedule::create([
                'class_id' => fake()->randomElement($classes),
                'subject_id' => fake()->randomElement($subjects),
                'teacher_id' => fake()->randomElement($teachers),

                'day' => fake()->randomElement($days),
                'start_time' => '07:00',
                'end_time' => '08:30',
            ]);
        }
    }
}