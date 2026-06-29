<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan prodi sudah ada
        $prodiList = Prodi::all();

        if ($prodiList->isEmpty()) {
            $this->command->error('❌ Data Prodi belum ada! Jalankan ProdiSeeder dulu.');
            return;
        }

        // Kosongkan data mahasiswa
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Mahasiswa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Nama dummy Indonesia
        $namaDepan = ['Ahmad', 'Muhammad', 'Ari', 'Rizki', 'Fajar', 'Dimas', 'Putra', 'Andi', 'Budi', 'Rina', 'Siti', 'Aisyah', 'Nabila', 'Putri'];
        $namaBelakang = ['Santoso', 'Pratama', 'Wijaya', 'Saputra', 'Hidayat', 'Kurniawan', 'Ramadhan', 'Maulana'];

        $total = 100; // 🔥 jumlah dummy mahasiswa

        for ($i = 1; $i <= $total; $i++) {

            $nama = 
                $namaDepan[array_rand($namaDepan)] . ' ' .
                $namaBelakang[array_rand($namaBelakang)];

            $prodi = $prodiList->random();

            Mahasiswa::create([
                'prodi_id' => $prodi->id,
                'nama_lengkap' => $nama,
                'nim' => '23' . str_pad($i, 6, '0', STR_PAD_LEFT),
            ]);
        }

        $this->command->info("🎉 MahasiswaSeeder selesai! Total: {$total} mahasiswa");
    }
}
