<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('status_pegawai', ['Aktif', 'Non-Aktif', 'Cuti'])
                  ->default('Aktif')
                  ->after('lokasi_kerja');
        });
        
        // Set semua data existing ke 'Aktif'
        DB::table('dosen')->update(['status_pegawai' => 'Aktif']);
    }

    public function down()
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('status_pegawai');
        });
    }
};