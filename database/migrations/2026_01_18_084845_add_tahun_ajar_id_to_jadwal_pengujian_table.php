<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->foreignId('tahun_ajar_id')
                ->nullable()
                ->after('id')
                ->constrained('tahun_ajar')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajar_id']);
            $table->dropColumn('tahun_ajar_id');
        });
    }
};