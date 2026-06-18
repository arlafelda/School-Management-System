<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // DATA UTAMA (FIXED MANUAL)
        // =========================
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG'],
            ['name' => 'IPA', 'code' => 'IPA'],
            ['name' => 'IPS', 'code' => 'IPS'],
            ['name' => 'Informatika', 'code' => 'INF'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'code' => $subject['code'],

                // 🔥 FIX: slug harus unik
                'slug' => Str::slug($subject['name']) . '-' . uniqid(),
            ]);
        }

        // =========================
        // DATA RANDOM (FACTORY)
        // =========================
        Subject::factory()->count(5)->create();
    }
}