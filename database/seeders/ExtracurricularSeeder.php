<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil hanya guru & siswa aktif (tidak di-trash).
        $teacherIds = Teacher::pluck('id');
        $studentIds = Student::pluck('id');

        if ($teacherIds->isEmpty()) {
            $this->call(TeacherSeeder::class);
            $teacherIds = Teacher::pluck('id');
        }

        if ($studentIds->isEmpty()) {
            $this->call(StudentSeeder::class);
            $studentIds = Student::pluck('id');
        }

        Extracurricular::factory()
            ->count(8)
            ->state(fn () => ['teacher_id' => $teacherIds->random()])
            ->create()
            ->each(function (Extracurricular $extracurricular) use ($studentIds) {
                if ($studentIds->isEmpty()) {
                    return;
                }

                // Tiap ekstrakurikuler diisi 3–8 siswa acak, tanpa duplikat.
                $jumlahSiswa = min(rand(3, 8), $studentIds->count());

                $extracurricular->students()->attach(
                    $studentIds->random($jumlahSiswa)
                );
            });
    }
}
