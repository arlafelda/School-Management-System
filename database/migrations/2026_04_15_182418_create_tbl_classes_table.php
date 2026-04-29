<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_classes', function (Blueprint $table) {
            $table->id();

            // contoh: 3D, 3A, 2TKJ
            $table->string('name')->unique();

            $table->string('major')->nullable(); // RPL, TKJ, dll

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_classes');
    }
};