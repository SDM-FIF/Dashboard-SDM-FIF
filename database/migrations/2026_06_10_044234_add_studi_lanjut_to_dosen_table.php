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
    Schema::table('dosen', function (Blueprint $table) {
        $table->string('status_studi_lanjut')->nullable()->after('status_dosen'); // izin_belajar / tugas_belajar
        $table->string('lokasi_kampus_studi')->nullable()->after('status_studi_lanjut');
        $table->year('tahun_mulai_studi')->nullable()->after('lokasi_kampus_studi');
        $table->year('batas_studi')->nullable()->after('tahun_mulai_studi');
    });
}

public function down(): void
{
    Schema::table('dosen', function (Blueprint $table) {
        $table->dropColumn(['status_studi_lanjut', 'lokasi_kampus_studi', 'tahun_mulai_studi', 'batas_studi']);
    });
}
};