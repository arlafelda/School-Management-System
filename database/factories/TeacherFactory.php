<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        // ambil user teacher secara acak
        $user = User::where('role', 'teacher')->inRandomOrder()->first();

        return [
            'user_id' => $user?->id,
            'name' => $user?->name ?? fake()->name(), // 🔥 WAJIB supaya slug tidak null
            'nip' => fake()->unique()->numerify('1980##########'),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'archived' => 0,
            'position' => fake()->randomElement(['Guru Mapel', 'Wali Kelas']),
        ];
    }
}