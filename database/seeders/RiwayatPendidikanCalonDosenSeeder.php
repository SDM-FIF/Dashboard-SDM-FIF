<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPendidikanCalonDosen;
use App\Models\CalonDosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikanCalonDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi apakah data calon dosen sudah ada
        $calonDosenList = CalonDosen::all();

        if ($calonDosenList->isEmpty()) {
            $this->command->error('❌ Data Calon Dosen belum ada! Jalankan CalonDosenSeeder dulu.');
            return;
        }

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Hapus data lama
            RiwayatPendidikanCalonDosen::truncate();
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat hapus data: ' . $e->getMessage());
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Data dummy riwayat pendidikan untuk setiap calon dosen
        $riwayatPendidikanData = [];

        // Mapping data pendidikan untuk setiap calon dosen
        // Format: [nama_calon => [jenjang => [data]]]
        $educationMapping = [
            // S3
            'Dr. Andi Firmansyah, M.Kom' => [
                'S1' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Universitas Indonesia', 'tahun_lulus' => 2007],
                'S2' => ['prodi' => 'Ilmu Komputer', 'universitas' => 'Institut Teknologi Bandung', 'tahun_lulus' => 2010],
                'S3' => ['prodi' => 'Computer Science', 'universitas' => 'National University of Singapore', 'tahun_lulus' => 2015],
            ],
            'Dr. Siti Rahmawati, M.T' => [
                'S1' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Institut Teknologi Bandung', 'tahun_lulus' => 2009],
                'S2' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Institut Teknologi Bandung', 'tahun_lulus' => 2012],
                'S3' => ['prodi' => 'Data Science', 'universitas' => 'University of Melbourne', 'tahun_lulus' => 2017],
            ],
            'Dr. Ahmad Wijaya, M.Sc' => [
                'S1' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Universitas Gadjah Mada', 'tahun_lulus' => 2005],
                'S2' => ['prodi' => 'Computer Science', 'universitas' => 'University of Queensland', 'tahun_lulus' => 2008],
                'S3' => ['prodi' => 'Computer Vision', 'universitas' => 'Tokyo Institute of Technology', 'tahun_lulus' => 2013],
            ],
            
            // S2
            'Budi Prasetyo, M.Kom' => [
                'S1' => ['prodi' => 'Sistem Informasi', 'universitas' => 'Universitas Airlangga', 'tahun_lulus' => 2012],
                'S2' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Institut Teknologi Sepuluh Nopember', 'tahun_lulus' => 2016],
            ],
            'Dewi Anggraini, M.T' => [
                'S1' => ['prodi' => 'Teknik Elektro', 'universitas' => 'Universitas Gadjah Mada', 'tahun_lulus' => 2014],
                'S2' => ['prodi' => 'Teknologi Informasi', 'universitas' => 'Universitas Gadjah Mada', 'tahun_lulus' => 2018],
            ],
            'Rina Kurniawati, M.Kom' => [
                'S1' => ['prodi' => 'Sistem Informasi', 'universitas' => 'Universitas Padjadjaran', 'tahun_lulus' => 2013],
                'S2' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Institut Teknologi Bandung', 'tahun_lulus' => 2017],
            ],
            
            // S1
            'Rudi Hermawan, S.Kom' => [
                'S1' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Universitas Diponegoro', 'tahun_lulus' => 2017],
            ],
            'Nina Kartika, S.Kom' => [
                'S1' => ['prodi' => 'Sistem Informasi', 'universitas' => 'Universitas Brawijaya', 'tahun_lulus' => 2018],
            ],
            'Fajar Nugroho, S.Kom' => [
                'S1' => ['prodi' => 'Teknik Informatika', 'universitas' => 'Universitas Sebelas Maret', 'tahun_lulus' => 2019],
            ],
        ];

        foreach ($calonDosenList as $calonDosen) {
            // Cari data pendidikan berdasarkan nama
            $educationData = $educationMapping[$calonDosen->nama] ?? null;
            
            if (!$educationData) {
                continue; // Skip jika tidak ada data pendidikan
            }
            
            // Loop untuk setiap jenjang pendidikan yang ada
            foreach ($educationData as $jenjang => $data) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => strtolower($jenjang),
                    'nama_universitas' => $data['universitas'],
                    'prodi_pendidikan' => $data['prodi'],
                    'tanggal_lulus' => Carbon::create($data['tahun_lulus'], 9, 15)->format('Y-m-d'),
                    'ijazah' => null, // File akan diupload manual nanti
                    'transkrip_nilai' => null, // File akan diupload manual nanti
                ];
            }
        }

        // Insert semua data
        foreach ($riwayatPendidikanData as $data) {
            RiwayatPendidikanCalonDosen::create($data);
        }

        // Statistik
        $totalRiwayat = count($riwayatPendidikanData);
        $totalS1 = collect($riwayatPendidikanData)->where('jenjang', 's1')->count();
        $totalS2 = collect($riwayatPendidikanData)->where('jenjang', 's2')->count();
        $totalS3 = collect($riwayatPendidikanData)->where('jenjang', 's3')->count();

        $this->command->info("✅ RiwayatPendidikanCalonDosenSeeder selesai!");
        $this->command->info("📊 Total riwayat pendidikan calon dosen: {$totalRiwayat}");
        $this->command->info("   - S1: {$totalS1}");
        $this->command->info("   - S2: {$totalS2}");
        $this->command->info("   - S3: {$totalS3}");
    }
}
