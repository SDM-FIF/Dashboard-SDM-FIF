<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelompokKeahlian;

class KelompokKeahlianSeeder extends Seeder
{
    public function run(): void
    {
        $kelompokKeahlian = [
            'Sistem Informasi',
            'Jaringan Komputer',
            'Kecerdasan Buatan',
            'Rekayasa Perangkat Lunak',
            'Basis Data',
            'Keamanan Siber',
            'Multimedia',
            'Pemrograman Web'
        ];

        foreach ($kelompokKeahlian as $nama) {
            KelompokKeahlian::create([
                'nama_kelompok_keahlian' => $nama
            ]);
        }

        $this->command->info('✅ Data Kelompok Keahlian berhasil dibuat!');
    }
}