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

        // Insert prodi dengan berbagai jenjang (S1, S2, S3)
        DB::table('prodi')->insert([
            // Jenjang S1
            ['id' => 1, 'fakultas_id' => 1, 'nama_prodi' => 'S1 - Informatika', 'jenjang' => 'S1'],
            ['id' => 2, 'fakultas_id' => 1, 'nama_prodi' => 'S1 - Rekayasa Perangkat Lunak', 'jenjang' => 'S1'],
            ['id' => 3, 'fakultas_id' => 1, 'nama_prodi' => 'S1 - Data Sains', 'jenjang' => 'S1'],
            ['id' => 4, 'fakultas_id' => 1, 'nama_prodi' => 'S1 - Teknologi Informasi', 'jenjang' => 'S1'],
            
            // Jenjang S2
            ['id' => 5, 'fakultas_id' => 1, 'nama_prodi' => 'S2 - Informatika', 'jenjang' => 'S2'],
            ['id' => 6, 'fakultas_id' => 1, 'nama_prodi' => 'S2 - Rekayasa Perangkat Lunak', 'jenjang' => 'S2'],
            ['id' => 7, 'fakultas_id' => 1, 'nama_prodi' => 'S2 - Data Sains', 'jenjang' => 'S2'],
            ['id' => 8, 'fakultas_id' => 1, 'nama_prodi' => 'S2 - Teknologi Informasi', 'jenjang' => 'S2'],
            
            // Jenjang S3
            ['id' => 9, 'fakultas_id' => 1, 'nama_prodi' => 'S3 - Informatika', 'jenjang' => 'S3'],
            ['id' => 10, 'fakultas_id' => 1, 'nama_prodi' => 'S3 - Rekayasa Perangkat Lunak', 'jenjang' => 'S3'],
            ['id' => 11, 'fakultas_id' => 1, 'nama_prodi' => 'S3 - Data Sains', 'jenjang' => 'S3'],
            ['id' => 12, 'fakultas_id' => 1, 'nama_prodi' => 'S3 - Teknologi Informasi', 'jenjang' => 'S3'],
        ]);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Data Prodi berhasil dibuat!');
        $this->command->info('📊 Total: 12 prodi (4 S1, 4 S2, 4 S3)');
    }
}