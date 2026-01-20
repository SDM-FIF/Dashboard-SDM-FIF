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
                'lokasi_kerja' => 'S1 PJJ Informatika',
                'pangkat_golongan' => 'Muda 2',
                'status_pegawai' => 'TPA Pegawai Tetap',
                'pendidikan_terakhir' => 'SMA',
            ],
            [
                'nama_lengkap' => 'Rina Marlina',
                'nip' => '12345679',
                'lokasi_kerja' => 'Fakultas Informatika',
                'pangkat_golongan' => 'Muda 1', 
                'status_pegawai' => 'TPA Pegawai Tetap',
                'pendidikan_terakhir' => 'SMK',
            ],
            [
                'nama_lengkap' => 'Ahmad Rizki',
                'nip' => '12345680',
                'lokasi_kerja' => 'BAAK',
                'pangkat_golongan' => 'Madya 1',
                'status_pegawai' => 'TPA Pegawai Kontrak',
                'pendidikan_terakhir' => 'D3',
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
                'pangkat_golongan' => $data['pangkat_golongan'],
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
