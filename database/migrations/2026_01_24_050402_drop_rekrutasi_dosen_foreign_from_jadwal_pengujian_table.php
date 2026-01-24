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
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            // Drop foreign key constraint dulu
            $table->dropForeign(['rekrutasi_dosen_id']);
            
            // Hapus column rekrutasi_dosen_id
            $table->dropColumn('rekrutasi_dosen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            // Kembalikan column rekrutasi_dosen_id
            $table->unsignedBigInteger('rekrutasi_dosen_id')->after('id');
            
            // Kembalikan foreign key constraint
            $table->foreign('rekrutasi_dosen_id')
                  ->references('id')
                  ->on('rekrutasi_dosen')
                  ->onDelete('cascade');
        });
    }
};
