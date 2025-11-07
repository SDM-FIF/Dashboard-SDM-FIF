<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'prodi_id' => 1,
                'nama_lengkap' => 'Ibrahim Hidayat',
                'nim' => 220101001,
            ],
            [
                'prodi_id' => 2,
                'nama_lengkap' => 'Rafi Alfarizi',
                'nim' => 220101002,
            ],
            [
                'prodi_id' => 3,
                'nama_lengkap' => 'Dinda Lestari',
                'nim' => 220101003,
            ],
            [
                'prodi_id' => 4,
                'nama_lengkap' => 'Andi Pratama',
                'nim' => 220101004,
            ],
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
