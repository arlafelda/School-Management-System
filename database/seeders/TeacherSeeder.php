<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua user dengan role teacher
        $teacherUsers = User::where('role', 'teacher')->get();

        foreach ($teacherUsers as $user) {

            // pastikan tidak null (biar slug aman)
            if (!$user->name) {
                continue;
            }

            Teacher::create([
                'user_id' => $user->id,
                'name' => $user->name, // 🔥 PENTING untuk slug di model kamu
                'nip' => fake()->unique()->numerify('1980##########'),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'archived' => 0,
                'position' => fake()->randomElement(['Guru Mapel', 'Wali Kelas']),
            ]);
        }

        // tambahan data random (pakai factory)
        Teacher::factory()->count(5)->create();
    }
}