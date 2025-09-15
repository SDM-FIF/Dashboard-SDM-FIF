<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prodi')->truncate();

        DB::table('prodi')->insert([
            ['id' => 1, 'fakultas_id' => 1, 'nama_prodi' => 'Informatika'],
            ['id' => 2, 'fakultas_id' => 1, 'nama_prodi' => 'Rekayasa Perangkat Lunak'],
            ['id' => 3, 'fakultas_id' => 1, 'nama_prodi' => 'Teknologi Informasi'],
            ['id' => 4, 'fakultas_id' => 1, 'nama_prodi' => 'Data Sains'],
        ]);
    }
}
