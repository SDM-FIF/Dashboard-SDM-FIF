<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->enum('jenjang', ['s1', 's2', 's3'])->after('nama_prodi');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            //
        });
    }
};
