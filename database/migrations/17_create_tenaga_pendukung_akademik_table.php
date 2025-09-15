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
        Schema::create('tenaga_pendukung_akademik', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');;
            $table->string('nama_lengkap');
            $table->string('nip');
            $table->string('pangkat_golongan')->nullable();
            $table->string('status_pegawai');
            $table->string('lokasi_kerja');
            $table->string('pendidikan_terakhir');

            $table->foreign('user_id')->references('id')->on('user');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_pendukung_akademik');
    }
};
