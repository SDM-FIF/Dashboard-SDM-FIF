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
            // Tambah kolom sertifikasi_dosen (boolean)
            $table->boolean('sertifikasi_dosen')->default(false)->after('pendidikan_terakhir');
            
            // Tambah kolom tanggal_serdos (date, nullable)
            $table->date('tanggal_serdos')->nullable()->after('sertifikasi_dosen');
            
            // Tambah kolom foto_profil (varchar, nullable)
            $table->string('foto_profil')->nullable()->after('tanggal_serdos');
            
            // Tambah kolom status_dosen (enum)
            $table->enum('status_dosen', [
                'Aktif',
                'Tugas Belajar',
                'Izin Belajar',
                'CLTY'
            ])->default('Aktif')->after('foto_profil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn([
                'sertifikasi_dosen',
                'tanggal_serdos',
                'foto_profil',
                'status_dosen'
            ]);
        });
    }
};