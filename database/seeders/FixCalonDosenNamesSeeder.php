<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;
use Illuminate\Support\Facades\DB;

class FixCalonDosenNamesSeeder extends Seeder
{
    /**
     * Fix incorrect name parsing for Budi and Eko
     */
    public function run(): void
    {
        $this->command->info('Memperbaiki nama calon dosen...');
        
        // Fix Budi Prasetyo
        $budi = CalonDosen::where('nama', 'Budi')->orWhere('nama', 'LIKE', 'Budi%')->first();
        if ($budi) {
            $budi->update([
                'front_title' => null,
                'nama' => 'Budi Prasetyo',
                'back_title' => 'M.Kom'
            ]);
            $this->command->info("✓ Fixed: {$budi->nama_lengkap}");
        } else {
            $this->command->warn("Budi tidak ditemukan");
        }
        
        // Fix Eko Setiawan
        $eko = CalonDosen::where('nama', 'Eko')->orWhere('nama', 'LIKE', 'Eko%')->first();
        if ($eko) {
            $eko->update([
                'front_title' => null,
                'nama' => 'Eko Setiawan',
                'back_title' => 'M.T'
            ]);
            $this->command->info("✓ Fixed: {$eko->nama_lengkap}");
        } else {
            $this->command->warn("Eko tidak ditemukan");
        }
        
        $this->command->info('Perbaikan selesai!');
    }
}
