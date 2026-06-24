<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $classIds = ClassModel::pluck('id');
        $subjects = Subject::with('teachers')->get();

        if ($classIds->isEmpty()) {
            $this->call(ClassModelSeeder::class);
            $classIds = ClassModel::pluck('id');
        }

        if ($subjects->isEmpty()) {
            $this->call(SubjectSeeder::class);
            $subjects = Subject::with('teachers')->get();
        }

        if (Teacher::count() === 0) {
            $this->call(TeacherSeeder::class);
            $subjects = Subject::with('teachers')->get();
        }

        // Hanya mapel yang punya minimal 1 guru pengajar, supaya schedule selalu valid.
        $subjectsWithTeacher = $subjects->filter(fn ($subject) => $subject->teachers->isNotEmpty());

        if ($subjectsWithTeacher->isEmpty()) {
            $this->command->warn('Tidak ada mapel yang memiliki guru pengajar. ScheduleSeeder dilewati.');
            return;
        }

        // Tiap kelas mendapat ~5 jadwal mata pelajaran (Senin-Jumat), pakai
        // data guru/mapel/kelas yang sudah ada, bukan membuat data baru.
        foreach ($classIds as $classId) {
            for ($i = 0; $i < 5; $i++) {
                $subject = $subjectsWithTeacher->random();
                $teacher = $subject->teachers->random();

                Schedule::factory()->create([
                    'class_id' => $classId,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                ]);
            }
        }
    }
}