<?php

namespace Database\Seeders;

use DeepCopy\f013\C;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,              
            UserSeeder::class,              
            FakultasSeeder::class,          
            ProdiSeeder::class,             
            KelompokKeahlianSeeder::class,
            TahunAjarSeeder::class,
            DosenSeeder::class,
            CalonDosenSeeder::class,
            RekrutasiDosenSeeder::class,
            TenagaPendukungAkademikSeeder::class,
            MahasiswaSeeder::class,
            JadwalPengujianSeeder::class             
        ]);
    }
}