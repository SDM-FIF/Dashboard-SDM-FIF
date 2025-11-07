<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KompetisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_kompetisi' => 'Lomba Inovasi Kampus',
                'nama_penyelenggara' => 'Universitas Andalas',
                'tingkat_kompetisi' => 'Universitas',
                'tanggal_kompetisi' => Carbon::create(2025, 3, 15),
            ],
            [
                'nama_kompetisi' => 'Hackathon Padang Raya',
                'nama_penyelenggara' => 'Dinas Kominfo Kota Padang',
                'tingkat_kompetisi' => 'Kabupaten/Kota',
                'tanggal_kompetisi' => Carbon::create(2025, 4, 20),
            ],
            [
                'nama_kompetisi' => 'Olimpiade Data Science Sumbar',
                'nama_penyelenggara' => 'Pemprov Sumatera Barat',
                'tingkat_kompetisi' => 'Provinsi',
                'tanggal_kompetisi' => Carbon::create(2025, 5, 10),
            ],
            [
                'nama_kompetisi' => 'Kompetisi AI Nasional',
                'nama_penyelenggara' => 'Kemendikbud',
                'tingkat_kompetisi' => 'Nasional',
                'tanggal_kompetisi' => Carbon::create(2025, 7, 25),
            ],
            [
                'nama_kompetisi' => 'ASEAN Tech Challenge',
                'nama_penyelenggara' => 'ASEAN Foundation',
                'tingkat_kompetisi' => 'Internasional',
                'tanggal_kompetisi' => Carbon::create(2025, 9, 1),
            ],
        ];

        DB::table('kompetisi')->insert($data);
    }
}
