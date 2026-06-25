<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_classes', function (Blueprint $table) {

            $table->id();

            // Nama kelas
            $table->string('name')->unique();

            // Slug URL
            $table->string('slug')->nullable()->unique();

            // Tingkat kelas
            $table->string('level');

            // Jurusan
            $table->string('major')->nullable();

            // Tahun ajaran
            $table->string('academic_year')->nullable();

            // Semester
            $table->string('semester')->nullable();

            // Wali kelas
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('tbl_teachers')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes(); // ✅ ganti archived - ada fitur arsip & restore
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_classes');
    }
};