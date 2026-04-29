<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_extracurricular_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained('tbl_extracurriculars')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('tbl_students')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_extracurricular_students');
    }
};