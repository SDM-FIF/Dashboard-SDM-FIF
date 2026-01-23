<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class CalonDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi Prodi
        $prodi = Prodi::all();
        
        if ($prodi->isEmpty()) {
            $this->command->error('❌ Data Prodi belum ada! Jalankan ProdiSeeder dulu.');
            return;
        }

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Hapus data lama
            CalonDosen::truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat hapus data: ' . $e->getMessage());
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // Data dummy calon dosen
        $calonDosenData = [
            // Calon dengan S3
            [
                // no_registrasi akan auto-generate
                'prodi_id' => $prodi->where('nama_prodi', 'Informatika')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Dr. Andi Firmansyah, M.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-03-15',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'prodi_pendidikan_s1' => 'Teknik Informatika',
                'nama_kampus_pendidikan_s1' => 'Universitas Indonesia',
                'ipk_s1' => 3.75,
                'prodi_pendidikan_s2' => 'Ilmu Komputer',
                'nama_kampus_pendidikan_s2' => 'Institut Teknologi Bandung',
                'ipk_s2' => 3.85,
                'prodi_pendidikan_s3' => 'Computer Science',
                'nama_kampus_pendidikan_s3' => 'National University of Singapore',
                'ipk_s3' => 3.90,
                'jabatan_fungsional_akademik' => 'Lektor',
                'prodi_tujuan' => 'Informatika',
                'bidang_keahlian' => 'Artificial Intelligence',
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'Data Sains')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Dr. Siti Rahmawati, M.T',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1987-07-22',
                'nomor_telepon' => '082345678901',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
                'prodi_pendidikan_s1' => 'Teknik Informatika',
                'nama_kampus_pendidikan_s1' => 'Institut Teknologi Bandung',
                'ipk_s1' => 3.80,
                'prodi_pendidikan_s2' => 'Teknik Informatika',
                'nama_kampus_pendidikan_s2' => 'Institut Teknologi Bandung',
                'ipk_s2' => 3.88,
                'prodi_pendidikan_s3' => 'Data Science',
                'nama_kampus_pendidikan_s3' => 'University of Melbourne',
                'ipk_s3' => 3.92,
                'jabatan_fungsional_akademik' => 'Lektor Kepala',
                'prodi_tujuan' => 'Data Sains',
                'bidang_keahlian' => 'Machine Learning',
            ],

            // Calon dengan S2
            [
                'prodi_id' => $prodi->where('nama_prodi', 'Rekayasa Perangkat Lunak')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Budi Prasetyo, M.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-05-10',
                'nomor_telepon' => '083456789012',
                'alamat' => 'Jl. Raya Darmo No. 78, Surabaya',
                'prodi_pendidikan_s1' => 'Sistem Informasi',
                'nama_kampus_pendidikan_s1' => 'Universitas Airlangga',
                'ipk_s1' => 3.65,
                'prodi_pendidikan_s2' => 'Teknik Informatika',
                'nama_kampus_pendidikan_s2' => 'Institut Teknologi Sepuluh Nopember',
                'ipk_s2' => 3.78,
                'prodi_pendidikan_s3' => null,
                'nama_kampus_pendidikan_s3' => null,
                'ipk_s3' => null,
                'jabatan_fungsional_akademik' => 'Asisten Ahli',
                'prodi_tujuan' => 'Rekayasa Perangkat Lunak',
                'bidang_keahlian' => 'Software Engineering',
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'Teknologi Informasi')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Dewi Anggraini, M.T',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1992-11-30',
                'nomor_telepon' => '084567890123',
                'alamat' => 'Jl. Kaliurang KM 5, Yogyakarta',
                'prodi_pendidikan_s1' => 'Teknik Elektro',
                'nama_kampus_pendidikan_s1' => 'Universitas Gadjah Mada',
                'ipk_s1' => 3.70,
                'prodi_pendidikan_s2' => 'Teknologi Informasi',
                'nama_kampus_pendidikan_s2' => 'Universitas Gadjah Mada',
                'ipk_s2' => 3.82,
                'prodi_pendidikan_s3' => null,
                'nama_kampus_pendidikan_s3' => null,
                'ipk_s3' => null,
                'jabatan_fungsional_akademik' => 'Asisten Ahli',
                'prodi_tujuan' => 'Teknologi Informasi',
                'bidang_keahlian' => 'Network Security',
            ],

            // Calon dengan S1
            [
                'prodi_id' => $prodi->where('nama_prodi', 'Informatika')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Rudi Hermawan, S.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1995-02-18',
                'nomor_telepon' => '085678901234',
                'alamat' => 'Jl. Pandanaran No. 12, Semarang',
                'prodi_pendidikan_s1' => 'Teknik Informatika',
                'nama_kampus_pendidikan_s1' => 'Universitas Diponegoro',
                'ipk_s1' => 3.60,
                'prodi_pendidikan_s2' => null,
                'nama_kampus_pendidikan_s2' => null,
                'ipk_s2' => null,
                'prodi_pendidikan_s3' => null,
                'nama_kampus_pendidikan_s3' => null,
                'ipk_s3' => null,
                'jabatan_fungsional_akademik' => 'NJFA',
                'prodi_tujuan' => 'Informatika',
                'bidang_keahlian' => 'Web Development',
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'Data Sains')->first()?->id ?? $prodi->first()->id,
                'nama' => 'Nina Kartika, S.Kom',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '1996-08-25',
                'nomor_telepon' => '086789012345',
                'alamat' => 'Jl. Veteran No. 34, Malang',
                'prodi_pendidikan_s1' => 'Sistem Informasi',
                'nama_kampus_pendidikan_s1' => 'Universitas Brawijaya',
                'ipk_s1' => 3.55,
                'prodi_pendidikan_s2' => null,
                'nama_kampus_pendidikan_s2' => null,
                'ipk_s2' => null,
                'prodi_pendidikan_s3' => null,
                'nama_kampus_pendidikan_s3' => null,
                'ipk_s3' => null,
                'jabatan_fungsional_akademik' => 'NJFA',
                'prodi_tujuan' => 'Data Sains',
                'bidang_keahlian' => 'Data Analytics',
            ],
        ];

        // Insert data (no_registrasi akan auto-generate via boot method)
        foreach ($calonDosenData as $data) {
            CalonDosen::create($data);
        }

        // Statistik
        $total = count($calonDosenData);
        $s3 = collect($calonDosenData)->whereNotNull('ipk_s3')->count();
        $s2 = collect($calonDosenData)->whereNull('ipk_s3')->whereNotNull('ipk_s2')->count();
        $s1 = collect($calonDosenData)->whereNull('ipk_s2')->whereNotNull('ipk_s1')->count();

        $this->command->info("✅ CalonDosenSeeder selesai!");
        $this->command->info("📊 Total calon dosen: {$total}");
        $this->command->info("   - Pendidikan S3: {$s3}");
        $this->command->info("   - Pendidikan S2: {$s2}");
        $this->command->info("   - Pendidikan S1: {$s1}");
        $this->command->info("🔢 No. Registrasi auto-generated dengan format: CAL-YYYYMMDD-XXXX");
    }
}