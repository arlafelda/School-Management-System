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
        // Jika kolom subject masih ada
        if (Schema::hasColumn('tbl_grades', 'subject')) {

            Schema::table('tbl_grades', function (Blueprint $table) {

                // Hapus kolom subject
                $table->dropColumn('subject');
            });
        }

        // Jika subject_id belum ada
        if (!Schema::hasColumn('tbl_grades', 'subject_id')) {

            Schema::table('tbl_grades', function (Blueprint $table) {

                // Tambah subject_id
                $table->unsignedBigInteger('subject_id');

                // Foreign key
                $table->foreign('subject_id')
                    ->references('id')
                    ->on('tbl_subjects')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika subject_id ada
        if (Schema::hasColumn('tbl_grades', 'subject_id')) {

            Schema::table('tbl_grades', function (Blueprint $table) {

                // Hapus foreign key
                $table->dropForeign(['subject_id']);

                // Hapus kolom
                $table->dropColumn('subject_id');
            });
        }

        // Jika subject belum ada
        if (!Schema::hasColumn('tbl_grades', 'subject')) {

            Schema::table('tbl_grades', function (Blueprint $table) {

                // Kembalikan subject
                $table->string('subject');
            });
        }
    }
};