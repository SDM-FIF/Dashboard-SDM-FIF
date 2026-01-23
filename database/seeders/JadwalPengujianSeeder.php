<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPengujian;
use App\Models\CalonDosen;
use App\Models\RekrutasiDosen;
use App\Models\Dosen;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalPengujianSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi data yang diperlukan
        $rekrutasiDosen = RekrutasiDosen::all();
        $dosen = Dosen::all();
        $calonDosen = CalonDosen::all();
        $tahunAjar = TahunAjar::all();

        if ($rekrutasiDosen->isEmpty()) {
            $this->command->error('❌ Data Rekrutasi Dosen belum ada! Jalankan RekrutasiDosenSeeder dulu.');
            return;
        }

        if ($dosen->isEmpty()) {
            $this->command->error('❌ Data Dosen belum ada! Jalankan DosenSeeder dulu.');
            return;
        }

         if ($calonDosen->isEmpty()) {
            $this->command->warn('⚠️ Data Calon Dosen kosong. Jadwal akan dibuat tanpa calon dosen.');
        }

        if ($tahunAjar->isEmpty()) {
            $this->command->warn('⚠️ Data Tahun Ajar kosong. Jadwal akan dibuat tanpa tahun ajar.');
        }

        try {
            // ✅ Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // ✅ Hapus data lama
            JadwalPengujian::truncate();
            
            // ✅ Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error saat hapus data: ' . $e->getMessage());
            
            // Pastikan foreign key check kembali enable
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return;
        }

        // ✅ Data dummy untuk gedung dan ruangan
        $gedungOptions = ['Gedung A', 'Gedung B', 'Gedung C', 'Gedung Rektorat', 'Gedung Teknik'];
        $ruanganOptions = ['R.101', 'R.201', 'R.301', 'Lab Komputer 1', 'Lab Komputer 2', 'Ruang Sidang', 'Aula'];
        $waktuOptions = ['08:00:00', '09:00:00', '10:00:00', '13:00:00', '14:00:00', '15:00:00'];

        $jadwalData = [];

        // Untuk setiap rekrutasi dosen, buat 2-3 jadwal pengujian dengan penguji berbeda
        foreach ($rekrutasiDosen as $rekrutasi) {
            // Ambil 2-3 dosen penguji secara acak
            $jumlahPenguji = rand(2, 3);
            $dosenPenguji = $dosen->random($jumlahPenguji);

            foreach ($dosenPenguji as $index => $penguji) {
                // Jadwal ujian bisa sama atau beda beberapa hari setelah tanggal pengujian rekrutasi
                $jadwalUjian = Carbon::parse($rekrutasi->tanggal_pengujian)
                    ->addDays(rand(0, 7)); // 0-7 hari setelah tanggal pengujian

                // Status: mayoritas Seleksi, beberapa Diterima/Ditolak
                $status = $this->generateStatus($index);

                $jadwalData[] = [
                    'tahun_ajar_id' => $tahunAjar->isNotEmpty() ? $tahunAjar->random()->id : null,
                    'calon_dosen_id' => $calonDosen->isNotEmpty() ? $calonDosen->random()->id : null,
                    'dosen_penguji_id' => $penguji->id,
                    'rekrutasi_dosen_id' => $rekrutasi->id,
                    'jadwal_ujian' => $jadwalUjian->format('Y-m-d'),
                    'gedung' => $gedungOptions[array_rand($gedungOptions)],  // ✅ Tambahan
                    'ruangan' => $ruanganOptions[array_rand($ruanganOptions)], // ✅ Tambahan
                    'waktu' => $waktuOptions[array_rand($waktuOptions)],      // ✅ Tambahan
                    'status_dosen' => $status,
                ];
            }
        }

        // Insert data
        DB::table('jadwal_pengujian')->insert($jadwalData);

        // Hitung statistik
        $totalJadwal = count($jadwalData);
        $countSeleksi = collect($jadwalData)->where('status_dosen', 'Seleksi')->count();
        $countDiterima = collect($jadwalData)->where('status_dosen', 'Diterima')->count();
        $countDitolak = collect($jadwalData)->where('status_dosen', 'Ditolak')->count();

        $this->command->info("✅ JadwalPengujianSeeder selesai!");
        $this->command->info("📊 Total jadwal: {$totalJadwal}");
        $this->command->info("   - Seleksi: {$countSeleksi}");
        $this->command->info("   - Diterima: {$countDiterima}");
        $this->command->info("   - Ditolak: {$countDitolak}");
    }

    /**
     * Generate status berdasarkan index untuk variasi data
     * Mayoritas Seleksi, beberapa Diterima/Ditolak
     */
    private function generateStatus($index): string
    {
        // 60% Seleksi, 25% Diterima, 15% Ditolak
        $rand = rand(1, 100);
        
        if ($rand <= 60) {
            return 'Seleksi';
        } elseif ($rand <= 85) {
            return 'Diterima';
        } else {
            return 'Ditolak';
        }
    }
}