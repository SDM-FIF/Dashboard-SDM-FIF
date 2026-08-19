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
        Schema::table('mahasiswa_kompetisi', function (Blueprint $table) {
            $table->string('sertifikat_file')->nullable()->after('juara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa_kompetisi', function (Blueprint $table) {
            $table->dropColumn('sertifikat_file');
        });
    }
};
