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
            // Tambah kolom no_registrasi (unique, auto-generate)
            $table->string('no_registrasi', 50)->unique()->nullable()->after('id');
            
            // Tambah kolom bidang_keahlian
            $table->string('bidang_keahlian')->nullable()->after('prodi_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->dropColumn(['no_registrasi', 'bidang_keahlian']);
        });
    }
};
