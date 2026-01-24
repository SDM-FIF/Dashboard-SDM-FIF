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
        Schema::table('riwayat_pendidikan_calon_dosen', function (Blueprint $table) {
            $table->string('nama_universitas')->nullable()->change();
            $table->string('prodi_pendidikan')->nullable()->change();
            $table->date('tanggal_lulus')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pendidikan_calon_dosen', function (Blueprint $table) {
            $table->string('nama_universitas')->nullable(false)->change();
            $table->string('prodi_pendidikan')->nullable(false)->change();
            $table->date('tanggal_lulus')->nullable(false)->change();
        });
    }
};