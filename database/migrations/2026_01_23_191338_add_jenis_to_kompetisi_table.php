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
        Schema::table('kompetisi', function (Blueprint $table) {
            // Menambahkan kolom jenis (enum kecil semua)
            $table->enum('jenis', ['sains', 'seni', 'olahraga', 'teknologi', 'lainnya'])
                ->default('sains')
                ->after('nama_kompetisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompetisi', function (Blueprint $table) {
            //
        });
    }
};
