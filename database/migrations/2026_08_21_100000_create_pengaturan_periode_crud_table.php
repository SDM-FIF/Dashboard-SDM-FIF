<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_periode_crud', function (Blueprint $table) {
            $table->id();
            $table->string('fitur')->unique();
            $table->string('mode')->default('selalu'); // selalu, rentang_tanggal
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        // Seed default settings for all core features
        $features = ['dosen', 'tpa', 'rekrutasi', 'mahasiswa', 'master', 'surat'];
        foreach ($features as $f) {
            DB::table('pengaturan_periode_crud')->insert([
                'fitur' => $f,
                'mode' => 'selalu',
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_periode_crud');
    }
};
