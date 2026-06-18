<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'nisn' => fake()->unique()->numerify('##########'),
            'nis' => fake()->unique()->numerify('########'),

            'gender' => fake()->randomElement(['L', 'P']),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),

            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),

            'major' => fake()->randomElement([
                'RPL',
                'TKJ',
                'AKL',
                'DKV',
            ]),

            'status' => 'aktif',

            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),

            'parent_phone' => fake()->phoneNumber(),
            'parent_address' => fake()->address(),

            'archived' => 0,
        ];
    }
}