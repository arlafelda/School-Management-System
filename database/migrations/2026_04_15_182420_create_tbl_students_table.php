<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_students', function (Blueprint $table) {

            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained('tbl_users')
                ->cascadeOnDelete();

            // Slug URL
            $table->string('slug')->unique();

            // Identitas siswa
            $table->string('nisn')->unique();
            $table->string('nis')->unique();

            // Nama siswa
            $table->string('name');

            // Jenis kelamin
            $table->enum('gender', ['L', 'P'])->nullable();

            // Tempat lahir
            $table->string('birth_place')->nullable();

            // Tanggal lahir
            $table->date('birth_date')->nullable();

            // Alamat
            $table->text('address')->nullable();

            // Nomor HP
            $table->string('phone')->nullable();

            // Relasi kelas
            $table->foreignId('class_id')
                ->nullable()
                ->constrained('tbl_classes')
                ->nullOnDelete();

            // Jurusan
            $table->string('major')->nullable();

            // Status siswa
            $table->enum('status', [
                'aktif',
                'lulus',
                'pindah'
            ])->default('aktif');

            // Data orang tua
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->text('parent_address')->nullable();

            $table->timestamps();
            $table->softDeletes(); // ✅ ganti archived - ada fitur arsip & restore
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_students');
    }
};