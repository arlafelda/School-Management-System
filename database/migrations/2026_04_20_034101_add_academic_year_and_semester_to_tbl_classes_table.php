<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->string('academic_year')->nullable()->after('major');
            $table->string('semester')->nullable()->after('academic_year');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_classes', function (Blueprint $table) {
            $table->dropColumn(['academic_year', 'semester']);
        });
    }
};
