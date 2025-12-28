<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekrutasiDosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class RekrutasiDosenSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua prodi yang ada
        $prodi = Prodi::all();
        
        if ($prodi->count() === 0) {
            $this->command->warn('⚠️  Tidak ada data prodi. Seed prodi terlebih dahulu.');
            return;
        }

        // Ambil ID prodi (ambil yang ada atau fallback ke first)
        $prodiInformatika = $prodi->where('nama_prodi', 'Informatika')->first()?->id ?? $prodi->first()->id;
        $prodiDataSains = $prodi->where('nama_prodi', 'Data Sains')->first()?->id ?? $prodi->skip(1)->first()?->id ?? $prodi->first()->id;
        $prodiRPL = $prodi->where('nama_prodi', 'Rekayasa Perangkat Lunak')->first()?->id ?? $prodi->skip(2)->first()?->id ?? $prodi->first()->id;
        $prodiTI = $prodi->where('nama_prodi', 'Teknologi Informasi')->first()?->id ?? $prodi->skip(3)->first()?->id ?? $prodi->first()->id;

        $data = [
            // Data Informatika
            [
                'no_registrasi' => 'REK-202412-0001',
                'nama_calon' => 'Dr. Ahmad Nurul Huda, M.Kom',
                'prodi_id' => $prodiInformatika,
                'tahun_ajar' => 'Ganjil 2025/2026',
                'tanggal_pengujian' => '2025-01-15',
                'jadwal' => 'Ruang 201, Pukul 09.00-12.00 WIB',
                'status' => 'Diproses',
            ],
            [
                'no_registrasi' => 'REK-202412-0002',
                'nama_calon' => 'Siti Aminah, S.Kom, M.T',
                'prodi_id' => $prodiInformatika,
                'tahun_ajar' => 'Ganjil 2025/2026',
                'tanggal_pengujian' => '2025-01-20',
                'jadwal' => 'Ruang 202, Pukul 13.00-16.00 WIB',
                'status' => 'Diterima',
            ],
            [
                'no_registrasi' => 'REK-202412-0003',
                'nama_calon' => 'Budi Santoso, M.Kom',
                'prodi_id' => $prodiInformatika,
                'tahun_ajar' => 'Genap 2025/2026',
                'tanggal_pengujian' => '2025-02-10',
                'jadwal' => 'Ruang 101, Pukul 10.00-13.00 WIB',
                'status' => 'Diterima',
            ],
            
            // Data Prodi Lain
            [
                'no_registrasi' => 'REK-202412-0004',
                'nama_calon' => 'Rina Wulandari, S.Kom, M.Kom',
                'prodi_id' => $prodiDataSains,
                'tahun_ajar' => 'Ganjil 2025/2026',
                'tanggal_pengujian' => '2025-01-18',
                'jadwal' => 'Ruang 301, Pukul 08.00-11.00 WIB',
                'status' => 'Diproses',
            ],
            [
                'no_registrasi' => 'REK-202412-0005',
                'nama_calon' => 'Agus Setiawan, M.T',
                'prodi_id' => $prodiRPL,
                'tahun_ajar' => 'Genap 2025/2026',
                'tanggal_pengujian' => '2025-02-15',
                'jadwal' => 'Ruang 102, Pukul 14.00-17.00 WIB',
                'status' => 'Ditolak',
            ],
            [
                'no_registrasi' => 'REK-202412-0006',
                'nama_calon' => 'Dewi Sartika, S.Kom, M.Sc',
                'prodi_id' => $prodiTI,
                'tahun_ajar' => 'Ganjil 2025/2026',
                'tanggal_pengujian' => '2025-01-25',
                'jadwal' => 'Ruang 203, Pukul 09.00-12.00 WIB',
                'status' => 'Ditolak',
            ],
        ];

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Hapus data lama
            RekrutasiDosen::truncate();
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            // Insert data baru
            foreach ($data as $item) {
                RekrutasiDosen::create($item);
            }
            
            $this->command->info('✅ Berhasil seed ' . count($data) . ' data rekrutasi dosen');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error: ' . $e->getMessage());
            
            // Pastikan foreign key check kembali enable
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}