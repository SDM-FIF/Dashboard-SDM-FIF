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
            // Add metode_pelaksanaan column with enum
            $table->enum('metode_pelaksanaan', ['Online', 'Onsite'])->default('Online')->after('jadwal_ujian');
            
            // Change gedung and ruangan to varchar
            $table->string('gedung')->nullable()->change();
            $table->string('ruangan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            // Remove metode_pelaksanaan column
            $table->dropColumn('metode_pelaksanaan');
        });
    }
};
