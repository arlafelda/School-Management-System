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
        Schema::create('tbl_grades', function (Blueprint $table) {
            $table->id();

            // 🔥 RELASI KE STUDENTS
            $table->foreignId('student_id')
                  ->constrained('tbl_students')
                  ->cascadeOnDelete();

            // DATA NILAI
            $table->string('subject');

            $table->unsignedInteger('assignment_score')->default(0);
            $table->unsignedInteger('mid_exam_score')->default(0);
            $table->unsignedInteger('final_exam_score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_grades');
    }
};