<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa_kompetisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('kompetisi_id');
            $table->enum('juara', ['Juara 1', 'Juara 2', 'Juara 3', 'Peserta'])->default('Peserta');
            $table->enum('jenis', ['Akademik', 'Non-Akademik'])->default('Akademik');

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('kompetisi_id')->references('id')->on('kompetisi')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_kompetisi');
    }

};
