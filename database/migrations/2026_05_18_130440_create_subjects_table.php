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

            $table->string('name');
            $table->string('code')->unique();
            $table->integer('kkm')->default(75);
            $table->text('description')->nullable();
            $table->string('slug')->unique();

            $table->boolean('archived')->default(false);

            $table->timestamps();

            // optional (kalau ingin optimasi pencarian)
            $table->index('name');
            $table->index('archived');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_subjects');
    }
};