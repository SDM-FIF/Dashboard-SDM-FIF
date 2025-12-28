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
        Schema::table('rekrutasi_dosen', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('no_registrasi')->unique()->after('id');
            $table->unsignedBigInteger('prodi_id')->nullable()->after('nama_calon');
            $table->string('tahun_ajar')->nullable()->after('prodi_id');
            $table->text('jadwal')->nullable()->after('tanggal_pengujian');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekrutasi_dosen', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn(['no_registrasi', 'prodi_id', 'tahun_ajar', 'jadwal', 'created_at', 'updated_at']);
        });
    }
};