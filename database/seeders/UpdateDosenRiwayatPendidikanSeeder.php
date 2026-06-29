<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\RiwayatPendidikanDosen;
use Faker\Factory as Faker;

class UpdateDosenRiwayatPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini akan menambahkan riwayat pendidikan untuk semua dosen yang belum memiliki riwayat pendidikan
     * berdasarkan pendidikan terakhir mereka.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Ambil semua dosen
        $dosens = Dosen::all();
        
        $this->command->info('Memulai update riwayat pendidikan dosen...');
        $this->command->info('Total dosen: ' . $dosens->count());
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($dosens as $dosen) {
            // Cek apakah dosen sudah memiliki riwayat pendidikan
            if ($dosen->riwayatPendidikan()->count() > 0) {
                $this->command->warn("Skip: {$dosen->nama_lengkap} - sudah memiliki riwayat pendidikan");
                $skipped++;
                continue;
            }
            
            $this->command->info("Processing: {$dosen->nama_lengkap} - Pendidikan Terakhir: {$dosen->pendidikan_terakhir}");
            
            // Daftar universitas untuk random selection
            $universities = [
                'Institut Teknologi Bandung',
                'Universitas Indonesia',
                'Universitas Gadjah Mada',
                'Institut Teknologi Sepuluh Nopember',
                'Universitas Brawijaya',
                'Universitas Diponegoro',
                'Universitas Padjadjaran',
                'Universitas Telkom',
                'Universitas Bina Nusantara',
                'Universitas Gunadarma'
            ];
            
            // Daftar prodi untuk random selection
            $prodiOptions = [
                'Teknik Informatika',
                'Ilmu Komputer',
                'Sistem Informasi',
                'Teknik Komputer',
                'Software Engineering',
                'Data Science',
                'Computer Science',
                'Information Technology'
            ];
            
            // Generate riwayat pendidikan berdasarkan pendidikan terakhir
            $jenjangList = [];
            
            switch ($dosen->pendidikan_terakhir) {
                case 'S3':
                    $jenjangList = ['S1', 'S2', 'S3'];
                    break;
                case 'S2':
                    $jenjangList = ['S1', 'S2'];
                    break;
                case 'S1':
                    $jenjangList = ['S1'];
                    break;
                default:
                    $jenjangList = ['S1'];
            }
            
            // Create riwayat for each jenjang
            $baseYear = 2000;
            foreach ($jenjangList as $index => $jenjang) {
                $yearGap = ($index == 0) ? 0 : (($jenjang == 'S2') ? 5 : ($jenjang == 'S3' ? 8 : 0));
                $graduationYear = $baseYear + $yearGap;
                
                $riwayat = new RiwayatPendidikanDosen();
                $riwayat->dosen_id = $dosen->id;
                $riwayat->jenjang = $jenjang;
                $riwayat->nama_universitas = $faker->randomElement($universities);
                $riwayat->prodi_pendidikan = $faker->randomElement($prodiOptions);
                $riwayat->tanggal_lulus = $faker->dateTimeBetween(
                    $graduationYear . '-01-01', 
                    $graduationYear . '-12-31'
                )->format('Y-m-d');
                
                // Tidak menambahkan file ijazah dan transkrip untuk data dummy
                $riwayat->ijazah = null;
                $riwayat->transkrip_nilai = null;
                
                $riwayat->save();
                
                $this->command->line("  ✓ Created {$jenjang} - {$riwayat->nama_universitas}");
            }
            
            $updated++;
        }
        
        $this->command->info('');
        $this->command->info('=================================');
        $this->command->info('Selesai!');
        $this->command->info("Total dosen diupdate: {$updated}");
        $this->command->info("Total dosen di-skip: {$skipped}");
        $this->command->info('=================================');
    }
}
