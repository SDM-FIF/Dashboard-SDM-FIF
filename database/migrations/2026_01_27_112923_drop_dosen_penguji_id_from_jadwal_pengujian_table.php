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
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->dropForeign(['dosen_penguji_id']);
            $table->dropColumn('dosen_penguji_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_penguji_id')->nullable()->after('calon_dosen_id');
            $table->foreign('dosen_penguji_id')->references('id')->on('dosen')->onDelete('set null');
        });
    }
};
