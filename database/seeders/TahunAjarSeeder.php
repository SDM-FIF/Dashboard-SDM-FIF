<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\DB;

class TahunAjarSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Hapus data lama
            TahunAjar::truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat hapus data: ' . $e->getMessage());
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Data tahun ajar
        $tahunAjarData = [
            // Tahun 2023/2024
            ['tahun' => 2324, 'semester' => '1'], 
            ['tahun' => 2324, 'semester' => '2'],
            
            // Tahun 2024/2025
            ['tahun' => 2425, 'semester' => '1'], 
            ['tahun' => 2425, 'semester' => '2'],
            
            // Tahun 2025/2026
            ['tahun' => 2526, 'semester' => '1'], 
            ['tahun' => 2526, 'semester' => '2'], 
            
            // Tahun 2026/2027
            ['tahun' => 2627, 'semester' => '1'], 
            ['tahun' => 2627, 'semester' => '2'],
            
            // Tahun 2027/2028
            ['tahun' => 2728, 'semester' => '1'], 
            ['tahun' => 2728, 'semester' => '2'], 
            
            // Tahun 2028/2029
            ['tahun' => 2829, 'semester' => '1'], 
            ['tahun' => 2829, 'semester' => '2'], 
        ];

        // Insert data
        foreach ($tahunAjarData as $data) {
            TahunAjar::create($data);
        }

        $this->command->info("✅ TahunAjarSeeder selesai!");
        $this->command->info("📊 Total tahun ajar: " . count($tahunAjarData));
        
        // Tampilkan preview
        $this->command->info("\n📅 Preview Tahun Ajar:");
        foreach (TahunAjar::all() as $ta) {
            $this->command->info("   - {$ta->label}");
        }
    }
}