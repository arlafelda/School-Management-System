<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_attendances', function (Blueprint $table) {

            $table->foreignId('subject_id')
                ->after('student_id')
                ->nullable()
                ->constrained('tbl_subjects')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('tbl_attendances', function (Blueprint $table) {

            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');

        });
    }
};