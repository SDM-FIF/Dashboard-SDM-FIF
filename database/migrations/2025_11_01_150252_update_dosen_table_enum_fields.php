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
            // Update status_pegawai menjadi enum
            $table->enum('status_pegawai', [
                'Tetap', 
                'Perbantuan', 
                'Profesional Full Time', 
                'Profesional Part Time'
            ])->default('Tetap')->change();

            // Update jabatan (JFA) menjadi enum 
            $table->enum('jabatan', [
                'NJFA',
                'Asisten Ahli',
                'Lektor', 
                'Lektor Kepala',
                'Profesor'
            ])->default('NJFA')->change();

            // Update lokasi_kerja menjadi enum
            $table->enum('lokasi_kerja', [
                'Informatika',
                'Rekayasa Perangkat Lunak', 
                'Data Sains',
                'Teknologi Informasi'
            ])->default('Informatika')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            // Kembalikan ke varchar
            $table->string('status_pegawai')->change();
            $table->string('jabatan')->nullable()->change();
            $table->string('lokasi_kerja')->change();
        });
    }
};
