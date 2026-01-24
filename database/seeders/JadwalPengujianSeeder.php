<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPengujian;
use App\Models\CalonDosen;
use App\Models\Dosen;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalPengujianSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi data yang diperlukan
        $dosen = Dosen::all();
        $calonDosen = CalonDosen::all();
        $tahunAjar = TahunAjar::all();

        if ($dosen->isEmpty()) {
            $this->command->error('❌ Data Dosen belum ada! Jalankan DosenSeeder dulu.');
            return;
        }

        if ($calonDosen->isEmpty()) {
            $this->command->error('❌ Data Calon Dosen belum ada! Jalankan CalonDosenSeeder dulu.');
            return;
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

        // Untuk setiap calon dosen, buat 2-3 jadwal pengujian dengan penguji berbeda
        foreach ($calonDosen as $calon) {
            // Ambil 2-3 dosen penguji secara acak
            $jumlahPenguji = rand(2, 3);
            $dosenPenguji = $dosen->random($jumlahPenguji);

            foreach ($dosenPenguji as $index => $penguji) {
                // Jadwal ujian random dalam 30 hari ke depan
                $jadwalUjian = Carbon::now()->addDays(rand(1, 30));

                $jadwalData[] = [
                    'tahun_ajar_id' => $tahunAjar->isNotEmpty() ? $tahunAjar->random()->id : null,
                    'calon_dosen_id' => $calon->id,
                    'dosen_penguji_id' => $penguji->id,
                    'jadwal_ujian' => $jadwalUjian->format('Y-m-d'),
                    'gedung' => $gedungOptions[array_rand($gedungOptions)],
                    'ruangan' => $ruanganOptions[array_rand($ruanganOptions)],
                    'waktu' => $waktuOptions[array_rand($waktuOptions)],
                ];
            }
        }

        // Insert data
        DB::table('jadwal_pengujian')->insert($jadwalData);

        // Hitung statistik
        $totalJadwal = count($jadwalData);

        $this->command->info("✅ JadwalPengujianSeeder selesai!");
        $this->command->info("📊 Total jadwal pengujian: {$totalJadwal}");
    }
}