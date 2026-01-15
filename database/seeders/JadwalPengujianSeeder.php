<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPengujian;
use App\Models\RekrutasiDosen;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalPengujianSeeder extends Seeder
{
    public function run(): void
    {
        // Validasi data yang diperlukan
        $rekrutasiDosen = RekrutasiDosen::all();
        $dosen = Dosen::all();

        if ($rekrutasiDosen->isEmpty()) {
            $this->command->error('❌ Data Rekrutasi Dosen belum ada! Jalankan RekrutasiDosenSeeder dulu.');
            return;
        }

        if ($dosen->isEmpty()) {
            $this->command->error('❌ Data Dosen belum ada! Jalankan DosenSeeder dulu.');
            return;
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

        $statusOptions = ['Seleksi', 'Diterima', 'Ditolak'];
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
                    'dosen_penguji_id' => $penguji->id,
                    'rekrutasi_dosen_id' => $rekrutasi->id,
                    'jadwal_ujian' => $jadwalUjian->format('Y-m-d'),
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