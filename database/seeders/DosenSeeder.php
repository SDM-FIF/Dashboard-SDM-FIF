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
use Carbon\Carbon;

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

        // Data dummy dosen dengan pendidikan terakhir dan kolom baru
        $dosenData = [
            // S1 - 3 dosen
            [
                'nama_lengkap' => 'Budi Santoso',
                'front_title' => '',
                'back_title' => 'S.Kom',
                'nip' => '199012152018031002',
                'kode_dosen' => 'DSN003',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Data Sains',
                'status_pegawai' => 'Profesional Full Time',
                'pendidikan_terakhir' => 'S1',
                'sertifikasi_dosen' => false,
                'tanggal_serdos' => null,
                'status_dosen' => 'Aktif',
                'username' => 'budi.santoso',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Rahmat Hidayat',
                'front_title' => '',
                'back_title' => 'S.Kom',
                'nip' => '199505102019031001',
                'kode_dosen' => 'DSN005',
                'jabatan' => 'NJFA',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Profesional Part Time',
                'pendidikan_terakhir' => 'S1',
                'sertifikasi_dosen' => false,
                'tanggal_serdos' => null,
                'status_dosen' => 'Tugas Belajar',
                'username' => 'rahmat.hidayat',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'front_title' => '',
                'back_title' => 'S.T',
                'nip' => '199408202020122002',
                'kode_dosen' => 'DSN009',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Profesional Part Time',
                'pendidikan_terakhir' => 'S1',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2023-06-15',
                'status_dosen' => 'Aktif',
                'username' => 'dewi.lestari',
                'password' => 'password123'
            ],
            
            // S2 - 4 dosen
            [
                'nama_lengkap' => 'Ahmad Fauzi',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198501012010011001',
                'kode_dosen' => 'DSN001',
                'jabatan' => 'Lektor',
                'lokasi_kerja' => 'Informatika',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S2',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2018-03-20',
                'status_dosen' => 'Aktif',
                'username' => 'ahmad.fauzi',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Indira Putri',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198709202012122001',
                'kode_dosen' => 'DSN004',
                'jabatan' => 'Lektor Kepala',
                'lokasi_kerja' => 'Teknologi Informasi',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S2',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2019-08-10',
                'status_dosen' => 'Aktif',
                'username' => 'indira.putri',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Maya Sari',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198712102015042001',
                'kode_dosen' => 'DSN006',
                'jabatan' => 'Lektor',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Perbantuan',
                'pendidikan_terakhir' => 'S2',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2020-01-25',
                'status_dosen' => 'Izin Belajar',
                'username' => 'maya.sari',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Andi Pratama',
                'front_title' => '',
                'back_title' => 'M.T',
                'nip' => '199203152019031003',
                'kode_dosen' => 'DSN007',
                'jabatan' => 'Asisten Ahli',
                'lokasi_kerja' => 'Data Sains',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S2',
                'sertifikasi_dosen' => false,
                'tanggal_serdos' => null,
                'status_dosen' => 'Aktif',
                'username' => 'andi.pratama',
                'password' => 'password123'
            ],
            
            // S3 - 3 dosen
            [
                'nama_lengkap' => 'Siti Nurhaliza',
                'front_title' => 'Prof. Dr.',
                'back_title' => 'M.T',
                'nip' => '198203051985032001',
                'kode_dosen' => 'DSN002',
                'jabatan' => 'Guru Besar',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2015-05-12',
                'status_dosen' => 'Aktif',
                'username' => 'siti.nurhaliza',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Bambang Sutopo',
                'front_title' => 'Prof. Dr. Ir.',
                'back_title' => 'M.Sc',
                'nip' => '197805121998031001',
                'kode_dosen' => 'DSN008',
                'jabatan' => 'Guru Besar',
                'lokasi_kerja' => 'Teknologi Informasi',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2012-11-30',
                'status_dosen' => 'CLTY',
                'username' => 'bambang.sutopo',
                'password' => 'password123'
            ],
            [
                'nama_lengkap' => 'Agus Setiawan',
                'front_title' => 'Dr.',
                'back_title' => 'M.T',
                'nip' => '198606152014031002',
                'kode_dosen' => 'DSN010',
                'jabatan' => 'Lektor Kepala',
                'lokasi_kerja' => 'Rekayasa Perangkat Lunak',
                'status_pegawai' => 'Profesional Full Time',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2021-09-18',
                'status_dosen' => 'Aktif',
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
            $selectedProdi = $prodi->random();
            $selectedKelompok = $kelompokKeahlian->random();

            $user = User::create([
                'fakultas_id' => $selectedProdi->fakultas_id,
                'prodi_id' => $selectedProdi->id,
                'role_id' => $dosenRole->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('dosen');

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
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
                'sertifikasi_dosen' => $data['sertifikasi_dosen'],      // ✅ Tambahan
                'tanggal_serdos' => $data['tanggal_serdos'],            // ✅ Tambahan
                'foto_profil' => null,                                  // ✅ Null dulu (foto diupload manual)
                'status_dosen' => $data['status_dosen'],                // ✅ Tambahan
            ]);

            $serdosStatus = $data['sertifikasi_dosen'] ? '✓ Serdos' : '✗ Belum';
            $this->command->info("✅ Dosen {$data['nama_lengkap']} ({$data['pendidikan_terakhir']}) - {$serdosStatus} - Status: {$data['status_dosen']}");
        }

        // Statistik
        $totalDosen = count($dosenData);
        $totalSerdos = collect($dosenData)->where('sertifikasi_dosen', true)->count();
        $totalBelumSerdos = $totalDosen - $totalSerdos;

        $this->command->info("🎉 DosenSeeder selesai!");
        $this->command->info("📊 Total: {$totalDosen} dosen");
        $this->command->info("   - Pembagian: 3 S1, 4 S2, 3 S3");
        $this->command->info("   - Sertifikasi: {$totalSerdos} sudah, {$totalBelumSerdos} belum");
    }
}