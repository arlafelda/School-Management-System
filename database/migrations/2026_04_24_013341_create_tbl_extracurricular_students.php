<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_extracurricular_students', function (Blueprint $table) {

            $table->id();

            // Ekstrakurikuler
            $table->foreignId('extracurricular_id')
                ->constrained('tbl_extracurriculars')
                ->cascadeOnDelete();

            // Siswa
            $table->foreignId('student_id')
                ->constrained('tbl_students')
                ->cascadeOnDelete();

            $table->timestamps();

            // Mencegah siswa terdaftar dua kali
            $table->unique(
                ['extracurricular_id', 'student_id'],
                'extra_student_unique'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_extracurricular_students');
    }
};