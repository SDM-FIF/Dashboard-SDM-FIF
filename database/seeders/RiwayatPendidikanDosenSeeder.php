<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPendidikanDosen;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikanDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi apakah data dosen sudah ada
        $dosenList = Dosen::all();

        if ($dosenList->isEmpty()) {
            $this->command->error('❌ Data Dosen belum ada! Jalankan DosenSeeder dulu.');
            return;
        }

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Hapus data lama
            RiwayatPendidikanDosen::truncate();
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat hapus data: ' . $e->getMessage());
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Data dummy riwayat pendidikan untuk setiap dosen
        $riwayatPendidikanData = [];

        foreach ($dosenList as $dosen) {
            // Tentukan jenjang pendidikan berdasarkan pendidikan_terakhir dosen
            $pendidikanTerakhir = $dosen->pendidikan_terakhir;
            
            // Data S1 (semua dosen pasti punya S1)
            $riwayatPendidikanData[] = [
                'dosen_id' => $dosen->id,
                'jenjang' => 'S1',
                'nama_universitas' => $this->getRandomUniversity(),
                'prodi_pendidikan' => $this->getRandomProdi('S1'),
                'tanggal_lulus' => Carbon::now()->subYears(rand(15, 25))->format('Y-m-d'),
                'ijazah' => null, // File akan diupload manual nanti
                'transkrip_nilai' => null, // File akan diupload manual nanti
            ];

            // Data S2 (jika pendidikan terakhir S2 atau S3)
            if (in_array($pendidikanTerakhir, ['S2', 'S3'])) {
                $riwayatPendidikanData[] = [
                    'dosen_id' => $dosen->id,
                    'jenjang' => 'S2',
                    'nama_universitas' => $this->getRandomUniversity(),
                    'prodi_pendidikan' => $this->getRandomProdi('S2'),
                    'tanggal_lulus' => Carbon::now()->subYears(rand(8, 15))->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }

            // Data S3 (jika pendidikan terakhir S3)
            if ($pendidikanTerakhir === 'S3') {
                $riwayatPendidikanData[] = [
                    'dosen_id' => $dosen->id,
                    'jenjang' => 'S3',
                    'nama_universitas' => $this->getRandomUniversity(),
                    'prodi_pendidikan' => $this->getRandomProdi('S3'),
                    'tanggal_lulus' => Carbon::now()->subYears(rand(3, 8))->format('Y-m-d'),
                    'ijazah' => null,
                    'transkrip_nilai' => null,
                ];
            }
        }

        // Insert semua data
        foreach ($riwayatPendidikanData as $data) {
            RiwayatPendidikanDosen::create($data);
        }

        // Statistik
        $totalRiwayat = count($riwayatPendidikanData);
        $totalS1 = collect($riwayatPendidikanData)->where('jenjang', 'S1')->count();
        $totalS2 = collect($riwayatPendidikanData)->where('jenjang', 'S2')->count();
        $totalS3 = collect($riwayatPendidikanData)->where('jenjang', 'S3')->count();

        $this->command->info("✅ RiwayatPendidikanDosenSeeder selesai!");
        $this->command->info("📊 Total riwayat pendidikan: {$totalRiwayat}");
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
