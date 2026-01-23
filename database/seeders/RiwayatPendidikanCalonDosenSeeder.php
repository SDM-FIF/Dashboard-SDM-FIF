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

        foreach ($calonDosenList as $calonDosen) {
            // Tentukan jenjang pendidikan berdasarkan data IPK yang ada
            $hasS1 = !is_null($calonDosen->ipk_s1);
            $hasS2 = !is_null($calonDosen->ipk_s2);
            $hasS3 = !is_null($calonDosen->ipk_s3);
            
            // Data S1 (jika ada IPK S1)
            if ($hasS1) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 'S1',
                    'nama_universitas' => $calonDosen->nama_kampus_pendidikan_s1 ?? $this->getRandomUniversity(),
                    'prodi_pendidikan' => $calonDosen->prodi_pendidikan_s1 ?? $this->getRandomProdi('S1'),
                    'tanggal_lulus' => Carbon::now()->subYears(rand(8, 20))->format('Y-m-d'),
                    'ijazah' => null, // File akan diupload manual nanti
                    'transkrip_nilai' => null, // File akan diupload manual nanti
                ];
            }

            // Data S2 (jika ada IPK S2)
            if ($hasS2) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 'S2',
                    'nama_universitas' => $calonDosen->nama_kampus_pendidikan_s2 ?? $this->getRandomUniversity(),
                    'prodi_pendidikan' => $calonDosen->prodi_pendidikan_s2 ?? $this->getRandomProdi('S2'),
                    'tanggal_lulus' => Carbon::now()->subYears(rand(4, 12))->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }

            // Data S3 (jika ada IPK S3)
            if ($hasS3) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 'S3',
                    'nama_universitas' => $calonDosen->nama_kampus_pendidikan_s3 ?? $this->getRandomUniversity(),
                    'prodi_pendidikan' => $calonDosen->prodi_pendidikan_s3 ?? $this->getRandomProdi('S3'),
                    'tanggal_lulus' => Carbon::now()->subYears(rand(1, 6))->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }
        }

        // Insert semua data
        foreach ($riwayatPendidikanData as $data) {
            RiwayatPendidikanCalonDosen::create($data);
        }

        // Statistik
        $totalRiwayat = count($riwayatPendidikanData);
        $totalS1 = collect($riwayatPendidikanData)->where('jenjang', 'S1')->count();
        $totalS2 = collect($riwayatPendidikanData)->where('jenjang', 'S2')->count();
        $totalS3 = collect($riwayatPendidikanData)->where('jenjang', 'S3')->count();

        $this->command->info("✅ RiwayatPendidikanCalonDosenSeeder selesai!");
        $this->command->info("📊 Total riwayat pendidikan calon dosen: {$totalRiwayat}");
        $this->command->info("   - S1: {$totalS1}");
        $this->command->info("   - S2: {$totalS2}");
        $this->command->info("   - S3: {$totalS3}");
    }

    /**
     * Generate random university name
     */
    private function getRandomUniversity(): string
    {
        $universities = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Diponegoro',
            'Universitas Brawijaya',
            'Universitas Airlangga',
            'Universitas Padjadjaran',
            'Universitas Hasanuddin',
            'National University of Singapore',
            'Nanyang Technological University',
            'University of Melbourne',
            'University of Queensland',
            'Tokyo Institute of Technology',
        ];

        return $universities[array_rand($universities)];
    }

    /**
     * Generate random prodi based on jenjang
     */
    private function getRandomProdi(string $jenjang): string
    {
        $prodiOptions = [
            'Teknik Informatika',
            'Ilmu Komputer',
            'Sistem Informasi',
            'Teknik Komputer',
            'Informatika',
            'Computer Science',
            'Software Engineering',
            'Data Science',
            'Information Technology',
            'Teknologi Informasi',
        ];

        return $prodiOptions[array_rand($prodiOptions)];
    }
}
