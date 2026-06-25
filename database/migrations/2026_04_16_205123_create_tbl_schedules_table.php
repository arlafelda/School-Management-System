<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_schedules', function (Blueprint $table) {

            $table->id();

            // Kelas
            $table->foreignId('class_id')
                ->constrained('tbl_classes')
                ->cascadeOnDelete();

            // Mata pelajaran
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('tbl_subjects')
                ->nullOnDelete();

            // Hari
            $table->string('day');

            // Jam mulai
            $table->time('start_time');

            // Jam selesai
            $table->time('end_time');

            // Guru pengajar
            $table->foreignId('teacher_id')
                ->constrained('tbl_teachers')
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes(); // ✅ ada fitur arsip & restore
                                   // ✅ melindungi relasi ke tbl_attendances & tbl_grades
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_schedules');
    }
};