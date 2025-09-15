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
        Schema::create('hasil_pengujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_pengujian_id');
            $table->unsignedBigInteger('total_nilai');
            $table->text('berita_acara');
            $table->timestamps();

            $table->foreign('jadwal_pengujian_id')->references('id')->on('jadwal_pengujian');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_pengujian');
    }
};
