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
        Schema::create('teacher_subject', function (Blueprint $table) {

            $table->id();

            // Guru
            $table->foreignId('teacher_id')
                ->constrained('tbl_teachers')
                ->cascadeOnDelete();

            // Mata pelajaran
            $table->foreignId('subject_id')
                ->constrained('tbl_subjects')
                ->cascadeOnDelete();

            $table->timestamps();

            // Mencegah relasi ganda
            $table->unique([
                'teacher_id',
                'subject_id'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject');
    }
};