<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TenagaPendukungAkademik;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class TenagaPendukungAkademikSeeder extends Seeder
{
    public function run(): void
    {
        // ===============================
        // RESET DATA
        // ===============================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $existingTPA = TenagaPendukungAkademik::all();
        foreach ($existingTPA as $tpa) {
            $user = $tpa->user;
            $tpa->delete();
            if ($user) {
                $user->delete();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ===============================
        // AMBIL ROLE TPA
        // ===============================
        $tpaRole = Role::where('name', 'tpa')->first();

        if (!$tpaRole) {
            $this->command->error('❌ Role "tpa" belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        // ===============================
        // DATA DUMMY TPA (BANYAK)
        // ===============================
        $tpaData = [
            [
                'nama_lengkap' => 'Ari Kurniawan',
                'nip' => '12345678',
                'lokasi_kerja' => 'LAA (Layanan Akademik)',
                'jabatan' => 'Staff Administrasi',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'D3',
            ],
            [
                'nama_lengkap' => 'Rina Marlina',
                'nip' => '12345679',
                'lokasi_kerja' => 'Unit SDM',
                'jabatan' => 'Staff Kepegawaian',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Ahmad Rizki',
                'nip' => '12345680',
                'lokasi_kerja' => 'BAAK',
                'jabatan' => 'Staff Registrasi',
                'status_pegawai' => 'Pegawai Kontrak',
                'pendidikan_terakhir' => 'D3',
            ],
            [
                'nama_lengkap' => 'Siti Aminah',
                'nip' => '12345681',
                'lokasi_kerja' => 'Prodi S1 Informatika',
                'jabatan' => 'Admin Prodi',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Eko Prasetyo',
                'nip' => '12345682',
                'lokasi_kerja' => 'Prodi S1 Teknologi Informasi',
                'jabatan' => 'Admin Prodi',
                'status_pegawai' => 'Pegawai Kontrak',
                'pendidikan_terakhir' => 'D4',
            ],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'nip' => '12345683',
                'lokasi_kerja' => 'LAA (Layanan Akademik)',
                'jabatan' => 'Staff Layanan',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'D3',
            ],
            [
                'nama_lengkap' => 'Hendri Wijaya',
                'nip' => '12345684',
                'lokasi_kerja' => 'Unit SDM',
                'jabatan' => 'Analisis SDM',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S2',
            ],
            [
                'nama_lengkap' => 'Budi Santoso',
                'nip' => '12345685',
                'lokasi_kerja' => 'Unit Logistik',
                'jabatan' => 'Staff Sarpras',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'SMA',
            ],
            [
                'nama_lengkap' => 'Fitri Handayani',
                'nip' => '12345686',
                'lokasi_kerja' => 'Prodi S1 Rekayasa Perangkat Lunak',
                'jabatan' => 'Admin Prodi',
                'status_pegawai' => 'Pegawai Kontrak',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Yusuf Habibi',
                'nip' => '12345687',
                'lokasi_kerja' => 'BAAK',
                'jabatan' => 'Staff Kelulusan',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Mega Utami',
                'nip' => '12345688',
                'lokasi_kerja' => 'LAA (Layanan Akademik)',
                'jabatan' => 'Staff Penjadwalan',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Bambang Hermawan',
                'nip' => '12345689',
                'lokasi_kerja' => 'Laboratorium Komputer',
                'jabatan' => 'Laboran',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'D3',
            ],
            [
                'nama_lengkap' => 'Andi Wijaya',
                'nip' => '12345690',
                'lokasi_kerja' => 'Prodi S1 Data Sains',
                'jabatan' => 'Admin Prodi',
                'status_pegawai' => 'Pegawai Kontrak',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'nama_lengkap' => 'Novianti',
                'nip' => '12345691',
                'lokasi_kerja' => 'LAA (Layanan Akademik)',
                'jabatan' => 'Staff Ujian',
                'status_pegawai' => 'Pegawai Kontrak',
                'pendidikan_terakhir' => 'D4',
            ],
            [
                'nama_lengkap' => 'Rahmat Hidayat',
                'nip' => '12345692',
                'lokasi_kerja' => 'Unit Keuangan',
                'jabatan' => 'Staff Keuangan',
                'status_pegawai' => 'Pegawai Tetap',
                'pendidikan_terakhir' => 'S1',
            ],
        ];


        // ===============================
        // INSERT DATA
        // ===============================
        foreach ($tpaData as $index => $data) {

            $user = User::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'username' => 'tpa' . ($index + 1),
                'password' => Hash::make('password123'),
                'role_id' => $tpaRole->id,
                'fakultas_id' => null,
                'prodi_id' => null,
            ]);

            TenagaPendukungAkademik::create([
                'user_id' => $user->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'nip' => $data['nip'],
                'jabatan' => $data['jabatan'],
                'status_pegawai' => $data['status_pegawai'],
                'lokasi_kerja' => $data['lokasi_kerja'],
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
            ]);

            $this->command->info("✅ TPA {$data['nama_lengkap']} berhasil dibuat");
        }

        $this->command->info("🎉 TenagaPendukungAkademikSeeder selesai!");
        $this->command->info("📊 Total: " . count($tpaData) . " TPA");
    }
}
