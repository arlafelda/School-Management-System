<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_subjects', function (Blueprint $table) {

            $table->id();

            // Nama mata pelajaran
            $table->string('name');

            // Kode mapel
            $table->string('code')->unique();

            // KKM
            $table->integer('kkm')->default(75);

            // Deskripsi
            $table->text('description')->nullable();

            // Slug URL
            $table->string('slug')->unique();

            $table->timestamps();
            $table->softDeletes(); // ✅ ganti archived - ada fitur arsip & restore

            // Index pencarian
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_subjects');
    }
};