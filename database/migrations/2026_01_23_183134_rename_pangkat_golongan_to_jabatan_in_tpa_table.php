<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tenaga_pendukung_akademik', function (Blueprint $table) {
            $table->renameColumn('pangkat_golongan', 'jabatan');
        });
    }

    public function down()
    {
        Schema::table('tenaga_pendukung_akademik', function (Blueprint $table) {
            $table->renameColumn('jabatan', 'pangkat_golongan');
        });
    }
};
