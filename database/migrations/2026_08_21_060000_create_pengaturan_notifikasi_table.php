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
        Schema::create('pengaturan_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('fitur')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        // Seed default notification settings
        $now = now();
        DB::table('pengaturan_notifikasi')->insert([
            ['fitur' => 'notif_dosen', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_tpa', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_rekrutasi', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_mahasiswa', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_master', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_notifikasi');
    }
};
