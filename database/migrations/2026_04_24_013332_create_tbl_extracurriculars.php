<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_extracurriculars', function (Blueprint $table) {

            $table->id();

            // Nama ekstrakurikuler
            $table->string('name');

            // Slug URL
            $table->string('slug')->unique();

            // Pembina ekstrakurikuler
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
        Schema::dropIfExists('tbl_extracurriculars');
    }
};