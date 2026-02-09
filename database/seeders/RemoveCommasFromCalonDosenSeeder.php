<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;
use Illuminate\Support\Facades\DB;

class RemoveCommasFromCalonDosenSeeder extends Seeder
{
    /**
     * Remove trailing commas from nama field in calon_dosen table
     */
    public function run(): void
    {
        $this->command->info('Menghapus koma dari nama calon dosen...');
        
        $calonDosens = CalonDosen::all();
        $updated = 0;
        
        foreach ($calonDosens as $calonDosen) {
            $originalNama = $calonDosen->nama;
            // Remove trailing comma and spaces
            $cleanedNama = rtrim(trim($originalNama), ',');
            $cleanedNama = trim($cleanedNama);
            
            if ($originalNama !== $cleanedNama) {
                $calonDosen->update(['nama' => $cleanedNama]);
                $this->command->info("✓ Updated: '{$originalNama}' → '{$cleanedNama}'");
                $updated++;
            }
        }
        
        $this->command->info('');
        $this->command->info("Total diupdate: {$updated}");
        $this->command->info('Selesai!');
    }
}
