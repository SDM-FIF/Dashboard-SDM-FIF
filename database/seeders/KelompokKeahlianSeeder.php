<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelompokKeahlian;
use Illuminate\Support\Facades\DB;

class KelompokKeahlianSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data lama
        KelompokKeahlian::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kelompokKeahlian = [
            'SEAL',  // Software Engineering and Application Laboratory
            'DSIS',  // Data Science and Information Systems
            'CITI'   // Cybersecurity and Information Technology Infrastructure
        ];

        foreach ($kelompokKeahlian as $nama) {
            KelompokKeahlian::create([
                'nama_kelompok_keahlian' => $nama
            ]);
        }

        $this->command->info('✅ Data Kelompok Keahlian berhasil dibuat!');
    }
}