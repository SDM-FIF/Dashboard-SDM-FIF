<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaKompetisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'mahasiswa_id' => 1,
                'kompetisi_id' => 1,
                'juara' => 'Juara 1',
                'jenis' => 'Akademik',
            ],
            [
                'mahasiswa_id' => 2,
                'kompetisi_id' => 2,
                'juara' => 'Juara 2',
                'jenis' => 'Non-Akademik',
            ],
            [
                'mahasiswa_id' => 3,
                'kompetisi_id' => 3,
                'juara' => 'Juara 3',
                'jenis' => 'Akademik',
            ],
            [
                'mahasiswa_id' => 4,
                'kompetisi_id' => 4,
                'juara' => 'Juara 1',
                'jenis' => 'Non-Akademik',
            ],

        ];

        DB::table('mahasiswa_kompetisi')->insert($data);
    }
}
