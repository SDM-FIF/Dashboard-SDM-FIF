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
        Schema::create('kompetisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kompetisi');
            $table->string('nama_penyelenggara');
            $table->enum('tingkat_kompetisi', ['Universitas', 'Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional']);
            $table->date('tanggal_kompetisi');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompetisi');
    }
};
