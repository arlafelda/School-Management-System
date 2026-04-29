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
        Schema::create('tbl_students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('tbl_users')
                ->onDelete('cascade');

            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->string('name');

            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            $table->text('address')->nullable();
            $table->string('phone')->nullable();

            $table->foreignId('class_id')
                ->nullable()
                ->constrained('tbl_classes')
                ->nullOnDelete();

            $table->string('major')->nullable();

            $table->enum('status', ['aktif', 'lulus', 'pindah'])->default('aktif');

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->text('parent_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_students');
    }
};
