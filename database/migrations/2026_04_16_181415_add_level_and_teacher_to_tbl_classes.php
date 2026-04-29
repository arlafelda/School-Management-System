<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->string('level')->after('name');
            $table->unsignedBigInteger('teacher_id')->nullable()->after('major');

            // optional (relasi foreign key)
            $table->foreign('teacher_id')->references('id')->on('tbl_teachers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['level', 'teacher_id']);
        });
    }
};