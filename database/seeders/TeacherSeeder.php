<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $subjectIds = Subject::pluck('id');

        // Akun guru tetap untuk testing login, sekaligus jadi Wali Kelas.
        $knownUser = User::factory()->teacher()->create([
            'name' => 'Budi Santoso',
            'slug' => Str::slug('Budi Santoso'),
            'email' => 'guru@gamelab.id',
            'password' => Hash::make('password'),
        ]);

        $knownTeacher = Teacher::factory()->waliKelas()->create([
            'user_id' => $knownUser->id,
            'name' => 'Budi Santoso',
            'slug' => Str::slug('Budi Santoso'),
        ]);

        $knownTeacher->subjects()->attach(
            $subjectIds->random(min(3, $subjectIds->count()))
        );

        // 7 guru tambahan sebagai Wali Kelas (total Wali Kelas = 8, cukup untuk 8 kelas).
        Teacher::factory()
            ->waliKelas()
            ->count(7)
            ->create()
            ->each(function (Teacher $teacher) use ($subjectIds) {
                $teacher->subjects()->attach(
                    $subjectIds->random(min(rand(1, 3), $subjectIds->count()))
                );
            });

        // 2 guru tambahan sebagai guru biasa (bukan Wali Kelas).
        Teacher::factory()
            ->count(2)
            ->create()
            ->each(function (Teacher $teacher) use ($subjectIds) {
                $teacher->subjects()->attach(
                    $subjectIds->random(min(rand(1, 3), $subjectIds->count()))
                );
            });
    }
}