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
        Schema::dropIfExists('rekrutasi_dosen');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('rekrutasi_dosen', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi')->unique();
            $table->string('nama_calon');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->unsignedBigInteger('prodi_id');
            $table->string('tahun_ajar');
            $table->date('tanggal_pengujian');
            $table->string('jadwal')->nullable();
            $table->enum('status', ['Diajukan', 'Diproses', 'Diterima', 'Ditolak']);
            $table->timestamps();
            
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }
};
