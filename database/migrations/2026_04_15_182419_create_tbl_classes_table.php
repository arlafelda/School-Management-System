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
        Schema::create('tbl_classes', function (Blueprint $table) {

            $table->id();

            // Nama kelas
            // Contoh: X RPL 1, XI TKJ 2
            $table->string('name')->unique();

            // Slug URL
            $table->string('slug')->nullable()->unique();

            // Status arsip
            $table->boolean('archived')->default(false);

            // Tingkat kelas
            // Contoh: X, XI, XII
            $table->string('level');

            // Jurusan
            // Contoh: RPL, TKJ, AKL
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_classes');
    }
};