<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassModelSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya ambil guru posisi "Wali Kelas" yang aktif (tidak di-trash).
        $teacherIds = Teacher::where('position', 'Wali Kelas')->pluck('id');

        if ($teacherIds->isEmpty()) {
            $this->call(TeacherSeeder::class);
            $teacherIds = Teacher::where('position', 'Wali Kelas')->pluck('id');
        }

        if ($teacherIds->isEmpty()) {
            $this->command->warn('Tidak ada guru dengan posisi "Wali Kelas". ClassModelSeeder dilewati.');
            return;
        }

        $shuffledTeacherIds = $teacherIds->shuffle()->values();

        // Jumlah kelas dibatasi sebanyak guru wali kelas yang tersedia.
        $totalClasses = min(8, $shuffledTeacherIds->count());

        if ($shuffledTeacherIds->count() < 8) {
            $this->command->warn(
                "Hanya ada {$shuffledTeacherIds->count()} guru dengan posisi \"Wali Kelas\", " .
                "jumlah kelas yang dibuat disesuaikan menjadi {$totalClasses} (bukan 8)."
            );
        }

        for ($i = 0; $i < $totalClasses; $i++) {
            ClassModel::factory()->create([
                'teacher_id' => $shuffledTeacherIds[$i],
            ]);
        }
    }
}
