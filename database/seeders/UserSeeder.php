<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // SUPER ADMIN (manual)
        // =========================
        User::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'super_admin',
            'archived' => 0,
            'creation_time' => now(),
            'create_id' => 1,
            'update_time' => now(),
            'update_id' => 1,
        ]);

        // =========================
        // ADMIN (10 data)
        // =========================
        User::factory()
            ->count(10)
            ->state(fn () => [
                'role' => 'admin',
                'password' => Hash::make('123456'),
            ])
            ->create();

        // =========================
        // TEACHER (20 data)
        // =========================
        User::factory()
            ->count(20)
            ->state(fn () => [
                'role' => 'teacher',
                'password' => Hash::make('123456'),
            ])
            ->create();

        // =========================
        // STUDENT (50 data)
        // =========================
        User::factory()
            ->count(50)
            ->state(fn () => [
                'role' => 'student',
                'password' => Hash::make('123456'),
            ])
            ->create();
    }
}