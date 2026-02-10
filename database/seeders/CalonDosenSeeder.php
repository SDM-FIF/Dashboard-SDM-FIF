<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalonDosen;
use App\Models\Prodi;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\DB;

class CalonDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi Prodi dan Tahun Ajar
        $prodi = Prodi::all();
        $tahunAjar = TahunAjar::all();
        
        if ($prodi->isEmpty()) {
            $this->command->error('❌ Data Prodi belum ada! Jalankan ProdiSeeder dulu.');
            return;
        }

        if ($tahunAjar->isEmpty()) {
            $this->command->error('❌ Data Tahun Ajar belum ada! Jalankan TahunAjarSeeder dulu.');
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

        // Data dummy calon dosen dengan status bervariasi (S1, S2, S3)
        $calonDosenData = [
            // Calon Dosen S3
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S3 - Informatika')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => 'Dr.',
                'nama' => 'Andi Firmansyah',
                'back_title' => 'M.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-03-15',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'jabatan_fungsional_akademik' => 'Lektor',
                'bidang_keahlian' => 'Artificial Intelligence',
                'status_penerimaan' => 'Diterima',
                'jalur_lamaran' => 'S3 Prof Full time',
                'h_index' => 12.5,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S3 - Data Sains')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => 'Dr.',
                'nama' => 'Siti Rahmawati',
                'back_title' => 'M.T',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1987-07-22',
                'nomor_telepon' => '082345678901',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
                'jabatan_fungsional_akademik' => 'Lektor Kepala',
                'bidang_keahlian' => 'Machine Learning',
                'status_penerimaan' => 'Diterima',
                'jalur_lamaran' => 'S2 Prof Full time',
                'h_index' => 8.3,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S3 - Rekayasa Perangkat Lunak')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => 'Dr.',
                'nama' => 'Ahmad Wijaya',
                'back_title' => 'M.Sc',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1983-09-12',
                'nomor_telepon' => '089012345678',
                'alamat' => 'Jl. Thamrin No. 89, Jakarta',
                'jabatan_fungsional_akademik' => 'Lektor',
                'bidang_keahlian' => 'Computer Vision',
                'status_penerimaan' => 'Seleksi',
                'jalur_lamaran' => 'S3 OnGoing',
                'h_index' => 4.7,
            ],

            // Calon Dosen S2
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S2 - Informatika')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Budi Prasetyo',
                'back_title' => 'M.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-05-10',
                'nomor_telepon' => '083456789012',
                'alamat' => 'Jl. Raya Darmo No. 78, Surabaya',
                'jabatan_fungsional_akademik' => 'Asisten Ahli',
                'bidang_keahlian' => 'Software Engineering',
                'status_penerimaan' => 'Seleksi',
                'jalur_lamaran' => 'S2 Praktisi Part time',
                'h_index' => 3.2,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S2 - Teknologi Informasi')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Dewi Anggraini',
                'back_title' => 'M.T',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1992-11-30',
                'nomor_telepon' => '084567890123',
                'alamat' => 'Jl. Kaliurang KM 5, Yogyakarta',
                'jabatan_fungsional_akademik' => 'Asisten Ahli',
                'bidang_keahlian' => 'Network Security',
                'status_penerimaan' => 'Diterima',
                'jalur_lamaran' => 'S2 Praktisi Part time',
                'h_index' => 1.8,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S2 - Teknik Komputer')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Eko Setiawan',
                'back_title' => 'M.T',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1988-02-18',
                'nomor_telepon' => '085678901234',
                'alamat' => 'Jl. Malioboro No. 12, Yogyakarta',
                'jabatan_fungsional_akademik' => 'Lektor',
                'bidang_keahlian' => 'Computer Networks',
                'status_penerimaan' => 'Ditolak',
                'jalur_lamaran' => 'S2 Prof Full time',
                'h_index' => 6.5,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S2 - Data Sains')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Rina Kurniawati',
                'back_title' => 'M.Kom',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1991-03-20',
                'nomor_telepon' => '087654321098',
                'alamat' => 'Jl. Dago No. 56, Bandung',
                'jabatan_fungsional_akademik' => 'Asisten Ahli',
                'bidang_keahlian' => 'Database Systems',
                'status_penerimaan' => 'Seleksi',
                'jalur_lamaran' => 'S3 Praktisi Part time',
                'h_index' => 1.5,
            ],

            // Calon Dosen S1
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S1 - Informatika')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Rudi Hermawan',
                'back_title' => 'S.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1995-02-18',
                'nomor_telepon' => '085678901234',
                'alamat' => 'Jl. Pandanaran No. 12, Semarang',
                'jabatan_fungsional_akademik' => 'NJFA',
                'bidang_keahlian' => 'Web Development',
                'status_penerimaan' => 'Ditolak',
                'jalur_lamaran' => 'S3 OnGoing',
                'h_index' => 0.5,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S1 - Data Sains')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Nina Kartika',
                'back_title' => 'S.Kom',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '1996-08-25',
                'nomor_telepon' => '086789012345',
                'alamat' => 'Jl. Veteran No. 34, Malang',
                'jabatan_fungsional_akademik' => 'NJFA',
                'bidang_keahlian' => 'Data Analytics',
                'status_penerimaan' => 'Seleksi',
                'jalur_lamaran' => 'S3 Praktisi Part time',
                'h_index' => 1.2,
            ],
            [
                'prodi_id' => $prodi->where('nama_prodi', 'S1 - Rekayasa Perangkat Lunak')->first()?->id ?? $prodi->first()->id,
                'tahun_ajar_id' => $tahunAjar->random()->id,
                'front_title' => null,
                'nama' => 'Fajar Nugroho',
                'back_title' => 'S.Kom',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '1997-06-14',
                'nomor_telepon' => '081122334455',
                'alamat' => 'Jl. Slamet Riyadi No. 100, Solo',
                'jabatan_fungsional_akademik' => 'NJFA',
                'bidang_keahlian' => 'Mobile Development',
                'status_penerimaan' => 'Seleksi',
                'jalur_lamaran' => 'S3 OnGoing',
                'h_index' => 0.8,
            ],
        ];

        // Insert data (no_registrasi akan auto-generate via boot method)
        foreach ($calonDosenData as $data) {
            CalonDosen::create($data);
        }

        // Statistik
        $total = count($calonDosenData);
        $diterima = collect($calonDosenData)->where('status_penerimaan', 'Diterima')->count();
        $seleksi = collect($calonDosenData)->where('status_penerimaan', 'Seleksi')->count();
        $ditolak = collect($calonDosenData)->where('status_penerimaan', 'Ditolak')->count();

        $this->command->info("✅ CalonDosenSeeder selesai!");
        $this->command->info("📊 Total calon dosen: {$total}");
        $this->command->info("📋 Status Penerimaan:");
        $this->command->info("   - Diterima: {$diterima}");
        $this->command->info("   - Seleksi: {$seleksi}");
        $this->command->info("   - Ditolak: {$ditolak}");
        $this->command->info("🔢 No. Registrasi auto-generated dengan format: CAL-YYYYMMDD-XXXX");
    }
}
