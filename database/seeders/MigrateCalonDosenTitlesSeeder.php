<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;
use Illuminate\Support\Facades\DB;

class MigrateCalonDosenTitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini akan memisahkan gelar depan dan belakang dari field nama
     * dan memindahkannya ke field front_title dan back_title
     */
    public function run(): void
    {
        $this->command->info('Memulai migrasi data gelar calon dosen...');
        
        // Ambil semua calon dosen
        $calonDosens = CalonDosen::all();
        
        $this->command->info('Total calon dosen: ' . $calonDosens->count());
        
        $updated = 0;
        $skipped = 0;
        
        // List of common academic titles
        $frontTitles = ['Dr.', 'Prof.', 'Ir.', 'Drs.', 'Dra.', 'dr.'];
        $backTitles = ['S.Kom', 'S.Kom.', 'M.Kom', 'M.Kom.', 'M.T', 'M.T.', 'M.Sc', 'M.Sc.', 
                       'Ph.D', 'Ph.D.', 'S.Si', 'S.Si.', 'S.T', 'S.T.', 'M.Si', 'M.Si.',
                       'S.E', 'S.E.', 'M.M', 'M.M.'];
        
        foreach ($calonDosens as $calonDosen) {
            $originalNama = $calonDosen->nama;
            $nama = $originalNama;
            $frontTitle = null;
            $backTitle = null;
            
            // Extract front titles
            foreach ($frontTitles as $title) {
                if (stripos($nama, $title) === 0) {
                    $frontTitle = $frontTitle ? $frontTitle . ' ' . $title : $title;
                    $nama = trim(substr($nama, strlen($title)));
                }
            }
            
            // Extract back titles (from the end)
            $namaParts = explode(' ', $nama);
            $extractedBackTitles = [];
            
            while (count($namaParts) > 0) {
                $lastPart = end($namaParts);
                $found = false;
                
                foreach ($backTitles as $title) {
                    if (stripos($lastPart, str_replace('.', '', $title)) !== false || $lastPart === $title) {
                        $extractedBackTitles[] = $lastPart;
                        array_pop($namaParts);
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    break;
                }
            }
            
            if (!empty($extractedBackTitles)) {
                $backTitle = implode(' ', array_reverse($extractedBackTitles));
                $nama = implode(' ', $namaParts);
            }
            
            $nama = trim($nama);
            
            // Update jika ada perubahan
            if ($nama !== $originalNama || $frontTitle || $backTitle) {
                try {
                    DB::table('calon_dosen')
                        ->where('id', $calonDosen->id)
                        ->update([
                            'front_title' => $frontTitle,
                            'nama' => $nama,
                            'back_title' => $backTitle,
                            'updated_at' => now()
                        ]);
                    
                    $this->command->info("✓ Updated: {$originalNama}");
                    $this->command->info("  Front: " . ($frontTitle ?? '-'));
                    $this->command->info("  Nama: {$nama}");
                    $this->command->info("  Back: " . ($backTitle ?? '-'));
                    $updated++;
                } catch (\Exception $e) {
                    $this->command->error("✗ Error updating {$originalNama}: " . $e->getMessage());
                }
            } else {
                $this->command->warn("Skip: {$originalNama} - tidak ada gelar yang terdeteksi");
                $skipped++;
            }
        }
        
        $this->command->info('');
        $this->command->info('=== Hasil Migrasi ===');
        $this->command->info("Total diproses: " . $calonDosens->count());
        $this->command->info("Berhasil diupdate: {$updated}");
        $this->command->info("Dilewati: {$skipped}");
        $this->command->info('Migrasi selesai!');
    }
}
