<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fakultas')->truncate();

        DB::table('fakultas')->insert([
            ['id' => 1, 'nama_fakultas' => 'Fakultas Informatika'],
        ]);
    }
}
