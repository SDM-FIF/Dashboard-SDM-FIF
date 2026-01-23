<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Kita gunakan enum agar pilihan status terkunci sesuai request-mu
            $table->enum('status', [
                'aktif',
                'cuti',
                'nonaktif',
                'lulus',
                'resign',
                'dikeluarkan'
            ])->default('aktif')->after('nim');
        });
    }

    public function down()
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
