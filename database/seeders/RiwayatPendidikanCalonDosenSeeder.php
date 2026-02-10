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
            $levels = $this->getEducationLevelsFromTitles($calonDosen->front_title, $calonDosen->back_title);

            $tahunS1 = Carbon::now()->subYears(rand(6, 12))->year;
            $tahunS2 = $tahunS1 + rand(2, 4);
            $tahunS3 = $tahunS2 + rand(3, 5);

            if (in_array('s1', $levels, true)) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 's1',
                    'nama_universitas' => $this->getRandomUniversity(),
                    'prodi_pendidikan' => $this->getRandomProdi('s1'),
                    'tanggal_lulus' => Carbon::create($tahunS1, 9, 15)->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }

            if (in_array('s2', $levels, true)) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 's2',
                    'nama_universitas' => $this->getRandomUniversity(),
                    'prodi_pendidikan' => $this->getRandomProdi('s2'),
                    'tanggal_lulus' => Carbon::create($tahunS2, 9, 15)->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }

            if (in_array('s3', $levels, true)) {
                $riwayatPendidikanData[] = [
                    'calon_dosen_id' => $calonDosen->id,
                    'jenjang' => 's3',
                    'nama_universitas' => $this->getRandomUniversity(),
                    'prodi_pendidikan' => $this->getRandomProdi('s3'),
                    'tanggal_lulus' => Carbon::create($tahunS3, 9, 15)->format('Y-m-d'),
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
        $totalS1 = collect($riwayatPendidikanData)->where('jenjang', 's1')->count();
        $totalS2 = collect($riwayatPendidikanData)->where('jenjang', 's2')->count();
        $totalS3 = collect($riwayatPendidikanData)->where('jenjang', 's3')->count();

        $this->command->info("✅ RiwayatPendidikanCalonDosenSeeder selesai!");
        $this->command->info("📊 Total riwayat pendidikan calon dosen: {$totalRiwayat}");
        $this->command->info("   - S1: {$totalS1}");
        $this->command->info("   - S2: {$totalS2}");
        $this->command->info("   - S3: {$totalS3}");
    }

    private function getEducationLevelsFromTitles(?string $frontTitle, ?string $backTitle): array
    {
        $front = strtolower(trim((string) $frontTitle));
        $back = strtolower(trim((string) $backTitle));

        $levels = ['s1'];

        if ($back !== '' && preg_match('/\bm\./', $back)) {
            $levels[] = 's2';
        }

        if ($front !== '' && preg_match('/\bdr\.?/', $front)) {
            $levels[] = 's3';
        }

        return array_values(array_unique($levels));
    }

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
