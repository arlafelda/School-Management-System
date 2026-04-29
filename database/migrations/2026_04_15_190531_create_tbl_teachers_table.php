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

            // RELASI LOGIN USER
            $table->foreignId('user_id')
                ->constrained('tbl_users')
                ->onDelete('cascade');

            // DATA GURU
            $table->string('nip')->unique();
            $table->string('name');

            $table->string('subject')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_teachers');
    }
};