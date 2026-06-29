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
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('tahun_ajar_id')->nullable()->after('prodi_id');
            $table->enum('status_penerimaan', ['Seleksi', 'Diterima', 'Ditolak'])->default('Seleksi')->after('bidang_keahlian');
            
            // Foreign key ke tahun_ajar
            $table->foreign('tahun_ajar_id')->references('id')->on('tahun_ajar')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajar_id']);
            $table->dropColumn(['tahun_ajar_id', 'status_penerimaan']);
        });
    }
};
