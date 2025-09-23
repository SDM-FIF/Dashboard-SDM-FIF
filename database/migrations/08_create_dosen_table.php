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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('prodi_id');
            $table->unsignedBigInteger('kelompok_keahlian_id');
            $table->string('front_title')->nullable();
            $table->string('nama_lengkap');
            $table->string('back_title');
            $table->string('jabatan')->nullable();
            $table->string('nip');
            $table->string('kode_dosen');
            $table->string('lokasi_kerja');
            

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('prodi_id')->references('id')->on('prodi');
            $table->foreign('kelompok_keahlian_id')->references('id')->on('kelompok_keahlian');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
