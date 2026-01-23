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
            $table->string('gedung', 100)->nullable()->after('jadwal_ujian');
            $table->string('ruangan', 100)->nullable()->after('gedung');
            $table->time('waktu')->nullable()->after('ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->dropColumn(['gedung', 'ruangan', 'waktu']);
        });
    }
};