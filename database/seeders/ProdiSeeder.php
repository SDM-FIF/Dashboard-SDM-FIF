<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate table
        DB::table('prodi')->truncate();

        // Insert prodi di Fakultas Informatika
        DB::table('prodi')->insert([
            ['id' => 1, 'fakultas_id' => 1, 'nama_prodi' => 'Informatika'],
            ['id' => 2, 'fakultas_id' => 1, 'nama_prodi' => 'Rekayasa Perangkat Lunak'],
            ['id' => 3, 'fakultas_id' => 1, 'nama_prodi' => 'Data Sains'],
            ['id' => 4, 'fakultas_id' => 1, 'nama_prodi' => 'Teknologi Informasi'],
        ]);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Data Prodi berhasil dibuat!');
    }
}