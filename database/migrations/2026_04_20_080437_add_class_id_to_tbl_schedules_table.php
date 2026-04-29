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
            $table->unsignedBigInteger('class_id')->after('id');

            $table->foreign('class_id')
                ->references('id')
                ->on('tbl_classes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_schedules', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
