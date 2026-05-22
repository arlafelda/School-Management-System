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
        Schema::table('tbl_schedules', function (Blueprint $table) {

            // ✅ tambah kolom subject_id
            $table->unsignedBigInteger('subject_id')->after('class_id')->nullable();

            // (opsional tapi disarankan) foreign key
            $table->foreign('subject_id')
                ->references('id')
                ->on('tbl_subjects')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_schedules', function (Blueprint $table) {

            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};