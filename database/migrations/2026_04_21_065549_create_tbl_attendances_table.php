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
        Schema::create('tbl_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('tbl_students')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('tbl_schedules')->cascadeOnDelete();

            $table->date('date');

            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);

            $table->timestamps();

            // 🔥 CEGAH DOUBLE INPUT
            $table->unique(['student_id', 'schedule_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_attendances');
    }
};
