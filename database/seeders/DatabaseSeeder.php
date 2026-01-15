<?php

namespace Database\Seeders;

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
            DosenSeeder::class,
            RekrutasiDosenSeeder::class,
            JadwalPengujianSeeder::class             
        ]);
    }
}