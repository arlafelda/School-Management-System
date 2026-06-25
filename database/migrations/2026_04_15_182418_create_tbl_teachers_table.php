<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_teachers', function (Blueprint $table) {

            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')
                ->constrained('tbl_users')
                ->cascadeOnDelete();

            // NIP guru
            $table->string('nip')->unique();

            // Nama guru
            $table->string('name');

            // Slug URL
            $table->string('slug')->nullable()->unique();

            // Jabatan
            $table->string('position')->nullable();

            // Nomor HP
            $table->string('phone')->nullable();

            // Alamat
            $table->text('address')->nullable();

            $table->timestamps();
            $table->softDeletes(); // ✅ ganti archived - ada fitur arsip & restore
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_teachers');
    }
};