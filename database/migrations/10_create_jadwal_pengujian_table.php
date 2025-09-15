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
        Schema::create('jadwal_pengujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_penguji_id');
            $table->unsignedBigInteger('rekrutasi_dosen_id');
            $table->date('jadwal_ujian');

            $table->foreign('dosen_penguji_id')->references('id')->on('dosen');
            $table->foreign('rekrutasi_dosen_id')->references('id')->on('rekrutasi_dosen');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengujian');
    }
};
