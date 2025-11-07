<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelompokKeahlian;

class KelompokKeahlianSeeder extends Seeder
{
    public function run(): void
    {
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