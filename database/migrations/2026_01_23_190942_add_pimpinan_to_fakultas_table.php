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
        Schema::table('fakultas', function (Blueprint $table) {
            // Menggunakan unsignedBigInteger agar cocok dengan primary key tabel dosen
            $table->unsignedBigInteger('dekan_id')->nullable()->after('nama_fakultas');
            $table->unsignedBigInteger('wadek1_id')->nullable()->after('dekan_id');
            $table->unsignedBigInteger('wadek2_id')->nullable()->after('wadek1_id');

            // Definisi Foreign Key (Opsional tapi disarankan)
            $table->foreign('dekan_id')->references('id')->on('dosen')->onDelete('set null');
            $table->foreign('wadek1_id')->references('id')->on('dosen')->onDelete('set null');
            $table->foreign('wadek2_id')->references('id')->on('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fakultas', function (Blueprint $table) {
            //
        });
    }
};
