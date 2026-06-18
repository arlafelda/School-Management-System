<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use Illuminate\Support\Str;

class ClassModelSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'X IPA 1', 'level' => 'X'],
            ['name' => 'X IPA 2', 'level' => 'X'],
            ['name' => 'XI IPA 1', 'level' => 'XI'],
            ['name' => 'XI IPA 2', 'level' => 'XI'],
            ['name' => 'XII IPA 1', 'level' => 'XII'],
        ];

        foreach ($classes as $class) {

            ClassModel::updateOrCreate(
                ['name' => $class['name']], // 🔥 CEK DUPLIKAT DI SINI
                [
                    'level' => $class['level'],
                    'teacher_id' => null,
                    'slug' => Str::slug($class['name']) . '-' . rand(1000, 9999),
                ]
            );
        }
    }
}