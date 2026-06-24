<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed akun login tetap (kredensial dikenal) untuk tiap role,
     * supaya mudah dites tanpa harus mencari data acak.
     *
     * Catatan: User untuk Teacher/Student dibuat otomatis lewat
     * TeacherFactory/StudentFactory (relasi user_id), jadi di sini
     * hanya akun super_admin & admin + beberapa user tambahan.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'slug' => Str::slug('Super Admin'),
            'email' => 'superadmin@gamelab.id',
            'password' => Hash::make('123456'),
            'role' => 'super_admin',
        ]);

        User::factory()->create([
            'name' => 'Admin Sekolah',
            'slug' => Str::slug('Admin Sekolah'),
            'email' => 'admin@gamelab.id',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);
    }
}
