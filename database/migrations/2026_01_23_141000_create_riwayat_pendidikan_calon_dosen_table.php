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
        Schema::create('riwayat_pendidikan_calon_dosen', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel calon_dosen
            $table->foreignId('calon_dosen_id')
                ->constrained('calon_dosen')
                ->onDelete('cascade');
            
            // Jenjang pendidikan
            $table->enum('jenjang', ['S1', 'S2', 'S3']);
            
            // Data universitas dan prodi
            $table->string('nama_universitas');
            $table->string('prodi_pendidikan');
            
            // Tanggal lulus
            $table->date('tanggal_lulus');
            
            // File ijazah dan transkrip (path/nama file)
            $table->string('ijazah')->nullable(); // Path file ijazah (PDF/JPG/PNG)
            $table->string('transkrip_nilai')->nullable(); // Path file transkrip (PDF/JPG/PNG)
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index('calon_dosen_id');
            $table->index('jenjang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikan_calon_dosen');
    }
};
