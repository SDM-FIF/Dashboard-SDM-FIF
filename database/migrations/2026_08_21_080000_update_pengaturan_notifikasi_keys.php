<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear old notification keys
        DB::table('pengaturan_notifikasi')->truncate();

        // Seed new CRUD notification keys
        $now = now();
        $features = ['dosen', 'tpa', 'rekrutasi', 'mahasiswa', 'master'];
        $actions = ['create', 'update', 'delete'];

        $data = [];
        foreach ($features as $fitur) {
            foreach ($actions as $action) {
                $data[] = [
                    'fitur' => "{$fitur}_{$action}",
                    'is_enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('pengaturan_notifikasi')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pengaturan_notifikasi')->truncate();

        $now = now();
        DB::table('pengaturan_notifikasi')->insert([
            ['fitur' => 'notif_dosen', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_tpa', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_rekrutasi', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_mahasiswa', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fitur' => 'notif_master', 'is_enabled' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
};
