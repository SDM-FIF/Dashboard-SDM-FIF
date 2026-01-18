<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            // Tambahkan kolom calon_dosen_id setelah kolom tertentu
            $table->foreignId('calon_dosen_id')
                ->nullable()
                ->after('id') // Sesuaikan posisinya
                ->constrained('calon_dosen')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->dropForeign(['calon_dosen_id']);
            $table->dropColumn('calon_dosen_id');
        });
    }
};