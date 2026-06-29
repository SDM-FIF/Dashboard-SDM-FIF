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
        Schema::table('penilaian_detail', function (Blueprint $table) {
            $table->boolean('rekomendasi_akhir')->default(false)->after('keterangan_berbobot');
            $table->unsignedBigInteger('prodi_rekomendasi')->nullable()->after('rekomendasi_akhir');
            $table->enum('status_rekomendasi', ['Full Time', 'Part Time'])->nullable()->after('prodi_rekomendasi');
            $table->enum('jfa_rekomendasi', ['NJFA', 'Asisten Ahli', 'Lektor', 'Lektor Kepala'])->nullable()->after('status_rekomendasi');
            $table->enum('pendidikan_rekomendasi', ['S2', 'S3'])->nullable()->after('jfa_rekomendasi');
            $table->unsignedBigInteger('kk_rekomendasi')->nullable()->after('pendidikan_rekomendasi');
            
            // Foreign keys
            $table->foreign('prodi_rekomendasi')->references('id')->on('prodi')->onDelete('set null');
            $table->foreign('kk_rekomendasi')->references('id')->on('kelompok_keahlian')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_detail', function (Blueprint $table) {
            $table->dropForeign(['prodi_rekomendasi']);
            $table->dropForeign(['kk_rekomendasi']);
            
            $table->dropColumn([
                'rekomendasi_akhir',
                'prodi_rekomendasi',
                'status_rekomendasi',
                'jfa_rekomendasi',
                'pendidikan_rekomendasi',
                'kk_rekomendasi'
            ]);
        });
    }
};
