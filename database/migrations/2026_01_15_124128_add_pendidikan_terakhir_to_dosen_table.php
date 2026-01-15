<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('dosen', function (Blueprint $table) {
        $table->enum('pendidikan_terakhir', ['S1', 'S2', 'S3'])
              ->default('S2') // TAMBAHKAN DEFAULT INI
              ->after('lokasi_kerja');
    });
}

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('pendidikan_terakhir');
        });
    }
};