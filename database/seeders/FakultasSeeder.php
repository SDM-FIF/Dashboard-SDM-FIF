<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate table
        DB::table('fakultas')->truncate();
        
        // Insert HANYA Fakultas Informatika
        DB::table('fakultas')->insert([
            ['id' => 1, 'nama_fakultas' => 'Fakultas Informatika'],
        ]);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Data Fakultas berhasil dibuat!');
    }
}