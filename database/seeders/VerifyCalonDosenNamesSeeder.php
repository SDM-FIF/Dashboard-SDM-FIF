<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;

class VerifyCalonDosenNamesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Verifikasi Data Calon Dosen ===');
        $this->command->info('');
        
        $calonDosen = CalonDosen::whereIn('nama', ['Budi Prasetyo', 'Eko Setiawan'])->get();
        
        foreach ($calonDosen as $cd) {
            $this->command->info("Calon Dosen ID: {$cd->id}");
            $this->command->info("  Gelar Depan   : " . ($cd->front_title ?? '-'));
            $this->command->info("  Nama          : {$cd->nama}");
            $this->command->info("  Gelar Belakang: {$cd->back_title}");
            $this->command->info("  Nama Lengkap  : {$cd->nama_lengkap}");
            $this->command->info('');
        }
    }
}
