<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;

class VerifyAllCalonDosenSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Verifikasi Semua Data Calon Dosen ===');
        $this->command->info('');
        
        $calonDosens = CalonDosen::orderBy('id')->get();
        
        $this->command->info("Total: {$calonDosens->count()} calon dosen");
        $this->command->info('');
        
        foreach ($calonDosens as $cd) {
            $frontTitle = $cd->front_title ? "{$cd->front_title} " : "";
            $backTitle = $cd->back_title ? " {$cd->back_title}" : "";
            
            $this->command->info(sprintf(
                "%-3s | %-15s | %-20s | %-15s | %s",
                $cd->id,
                $cd->front_title ?? '-',
                $cd->nama,
                $cd->back_title ?? '-',
                $cd->nama_lengkap
            ));
        }
        
        $this->command->info('');
        $this->command->info('Format: ID | Gelar Depan | Nama | Gelar Belakang | Nama Lengkap');
    }
}
