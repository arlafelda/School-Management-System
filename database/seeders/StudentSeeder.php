<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'student')->get();

        foreach ($users as $user) {

            Student::factory()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'class_id' => ClassModel::inRandomOrder()->value('id'),
            ]);

        }
    }
}