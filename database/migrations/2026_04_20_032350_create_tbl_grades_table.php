<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_grades', function (Blueprint $table) {

            $table->id();

            // Siswa
            $table->foreignId('student_id')
                ->constrained('tbl_students')
                ->cascadeOnDelete();

            // Jadwal
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('tbl_schedules')
                ->nullOnDelete();

            // Nilai tugas
            $table->unsignedInteger('assignment_score')->default(0);

            // Nilai UTS
            $table->unsignedInteger('mid_exam_score')->default(0);

            // Nilai UAS
            $table->unsignedInteger('final_exam_score')->default(0);

            // Mata pelajaran
            $table->foreignId('subject_id')
                ->constrained('tbl_subjects')
                ->cascadeOnDelete();

            $table->timestamps();
            // ❌ tidak pakai softDeletes
            // data nilai adalah data akademik permanen
            // jika ada kesalahan cukup gunakan fitur edit
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_grades');
    }
};