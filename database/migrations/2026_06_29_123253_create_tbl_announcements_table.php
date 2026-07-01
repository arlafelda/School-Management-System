<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['normal', 'penting', 'mendesak'])->default('normal');
            $table->enum('target_role', ['all', 'student', 'teacher', 'admin'])->default('all');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_announcements');
    }
};