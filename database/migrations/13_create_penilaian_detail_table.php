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
        Schema::create('penilaian_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('komponen_penilaian_id');
            $table->unsignedBigInteger('hasil_pengujian_id');
            $table->integer('skor');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('komponen_penilaian_id')->references('id')->on('komponen_penilaian');
            $table->foreign('hasil_pengujian_id')->references('id')->on('hasil_pengujian');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_detail');
    }
};
