<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Prodi;
use App\Models\KelompokKeahlian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data dosen dan user dosen yang ada
        $existingDosen = Dosen::all();
        foreach ($existingDosen as $dosen) {
            $user = $dosen->user;
            $dosen->delete();
            if ($user) {
                $user->delete();
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy dosen yang realistis dengan enum values baru
        $dosenData = [
            [
                'nama_lengkap' => 'Dr. Ahmad Fauzi, M.Kom',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198501012010011001',
                'kode_dosen' => 'DSN001',
                'jabatan' => 'Lektor',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Tetap',
                'username' => 'ahmad.fauzi',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Siti Nurhaliza, M.T',
                'front_title' => 'Prof. Dr.',
                'back_title' => 'M.T',
                'nip' => '198203051985032001',
                'kode_dosen' => 'DSN002',
                'jabatan' => 'Profesor',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Tetap',
                'username' => 'siti.nurhaliza',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Budi Santoso, S.Kom, M.Sc',
                'front_title' => '',
                'back_title' => 'S.Kom, M.Sc',
                'nip' => '199012152018031002',
                'kode_dosen' => 'DSN003',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Data Sains',
                'status_pegawai' => 'Profesional Full Time',
                'username' => 'budi.santoso',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Dr. Indira Putri, M.Kom',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198709202012122001',
                'kode_dosen' => 'DSN004',
                'jabatan' => 'Lektor Kepala',
                'lokasi_kerja' => 'Teknologi Informasi',
                'status_pegawai' => 'Tetap',
                'username' => 'indira.putri',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Rahmat Hidayat, M.T',
                'front_title' => '',
                'back_title' => 'M.T',
                'nip' => '199505102019031001',
                'kode_dosen' => 'DSN005',
                'jabatan' => 'NJFA',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Profesional Part Time',
                'username' => 'rahmat.hidayat',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Dr. Maya Sari, M.Kom',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198712102015042001',
                'kode_dosen' => 'DSN006',
                'jabatan' => 'Lektor',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Perbantuan',
                'username' => 'maya.sari',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Andi Pratama, S.Kom, M.T',
                'front_title' => '',
                'back_title' => 'S.Kom, M.T',
                'nip' => '199203152019031003',
                'kode_dosen' => 'DSN007',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Data Sains',
                'status_pegawai' => 'Tetap',
                'username' => 'andi.pratama',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Ir. Bambang Sutopo, M.Sc',
                'front_title' => 'Prof. Dr. Ir.',
                'back_title' => 'M.Sc',
                'nip' => '197805121998031001',
                'kode_dosen' => 'DSN008',
                'jabatan' => 'Profesor',
                'lokasi_kerja' => 'Teknologi Informasi',
                'status_pegawai' => 'Tetap',
                'username' => 'bambang.sutopo',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Dewi Lestari, M.Kom',
                'front_title' => '',
                'back_title' => 'M.Kom',
                'nip' => '199408202020122002',
                'kode_dosen' => 'DSN009',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Profesional Part Time',
                'username' => 'dewi.lestari',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Dr. Agus Setiawan, M.T',
                'front_title' => 'Dr.',
                'back_title' => 'M.T',
                'nip' => '198606152014031002',
                'kode_dosen' => 'DSN010',
                'jabatan' => 'Lektor Kepala',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Profesional Full Time',
                'username' => 'agus.setiawan',
                'password' => 'password123'
            ]
        ];

        // Validasi data yang diperlukan sudah ada
        $prodi = Prodi::all();
        $kelompokKeahlian = KelompokKeahlian::all();
        $dosenRole = Role::where('name', 'dosen')->first();

        if ($prodi->isEmpty()) {
            $this->command->error('❌ Data Prodi belum ada! Jalankan ProdiSeeder dulu.');
            return;
        }

        if ($kelompokKeahlian->isEmpty()) {
            $this->command->error('❌ Data Kelompok Keahlian belum ada! Jalankan KelompokKeahlianSeeder dulu.');
            return;
        }

        if (!$dosenRole) {
            $this->command->error('❌ Role "dosen" belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        foreach ($dosenData as $data) {
            // Pilih prodi dan kelompok keahlian secara acak
            $selectedProdi = $prodi->random();
            $selectedKelompok = $kelompokKeahlian->random();

            // Buat user terlebih dahulu
            $user = User::create([
                'fakultas_id' => $selectedProdi->fakultas_id,
                'prodi_id' => $selectedProdi->id,
                'role_id' => $dosenRole->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign role dosen
            $user->assignRole('dosen');

            // Buat data dosen
            Dosen::create([
                'user_id' => $user->id,
                'prodi_id' => $selectedProdi->id,
                'kelompok_keahlian_id' => $selectedKelompok->id,
                'front_title' => $data['front_title'],
                'nama_lengkap' => $data['nama_lengkap'],
                'back_title' => $data['back_title'],
                'jabatan' => $data['jabatan'],
                'nip' => $data['nip'],
                'kode_dosen' => $data['kode_dosen'],
                'lokasi_kerja' => $data['lokasi_kerja'],
                'status_pegawai' => $data['status_pegawai'],
            ]);

            $this->command->info("✅ Dosen {$data['nama_lengkap']} berhasil dibuat");
        }

        $this->command->info("🎉 DosenSeeder selesai! Total: " . count($dosenData) . " dosen");
    }
}