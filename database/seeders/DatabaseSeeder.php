<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,              // 1. Buat roles & permissions dulu
            UserSeeder::class,              // 2. Super admin (HANYA butuh role_id)
            FakultasSeeder::class,          // 3. Buat fakultas (master data)
            ProdiSeeder::class,             // 4. Buat prodi (butuh fakultas_id)
            KelompokKeahlianSeeder::class,  // 5. Buat kelompok keahlian
            DosenSeeder::class,
            MahasiswaSeeder::class,
            KompetisiSeeder::class,  
            MahasiswaKompetisiSeeder::class,
            TenagaPendukungAkademikSeeder::class,           
        ]);
    }
}