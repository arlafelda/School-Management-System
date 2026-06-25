<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil hanya kelas yang aktif (tidak di-trash).
        $classIds = ClassModel::pluck('id');

        if ($classIds->isEmpty()) {
            $this->call(ClassModelSeeder::class);
            $classIds = ClassModel::pluck('id');
        }

        // Akun siswa tetap untuk testing login.
        $knownUser = User::factory()->student()->create([
            'name'     => 'Nadia Putri',
            'slug'     => Str::slug('Nadia Putri'),
            'email'    => 'siswa@gamelab.id',
            'password' => Hash::make('password'),
        ]);

        Student::factory()->create([
            'user_id'  => $knownUser->id,
            'name'     => 'Nadia Putri',
            'slug'     => Str::slug('Nadia Putri') . '-' . $knownUser->id,
            'class_id' => $classIds->random(),
        ]);

        // 24 siswa tambahan tersebar acak di kelas yang ada.
        Student::factory()
            ->count(24)
            ->state(fn () => ['class_id' => $classIds->random()])
            ->create();
    }
}
