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
        Schema::table('tbl_grades', function (Blueprint $table) {

            $table->foreignId('schedule_id')
                ->nullable()
                ->after('student_id')
                ->constrained('tbl_schedules')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_grades', function (Blueprint $table) {

            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');

        });
    }
};