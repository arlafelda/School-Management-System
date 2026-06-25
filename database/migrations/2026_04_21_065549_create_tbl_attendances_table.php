<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_attendances', function (Blueprint $table) {

            $table->id();

            // Siswa
            $table->foreignId('student_id')
                ->constrained('tbl_students')
                ->cascadeOnDelete();

            // Mata pelajaran
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('tbl_subjects')
                ->nullOnDelete();

            // Jadwal
            $table->foreignId('schedule_id')
                ->constrained('tbl_schedules')
                ->cascadeOnDelete();

            // Tanggal absensi
            $table->date('date');

            // Status kehadiran
            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
                'alpa'
            ]);

            $table->timestamps();

            // Mencegah data absensi ganda
            $table->unique([
                'student_id',
                'schedule_id',
                'date'
            ]);
            // ❌ tidak pakai softDeletes
            // data absensi adalah data historis permanen
            // jika ada kesalahan cukup gunakan fitur edit status
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attendances');
    }
};