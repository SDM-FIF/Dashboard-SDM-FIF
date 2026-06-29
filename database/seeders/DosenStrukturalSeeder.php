<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Prodi;
use App\Models\KelompokKeahlian;
use App\Models\RiwayatPendidikanDosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DosenStrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Menambahkan 3 dosen dengan multi-role:
     * 1. Dosen + Dekan
     * 2. Dosen + Wadek 1
     * 3. Dosen + Wadek 2
     * 
     * Lengkap dengan riwayat pendidikan S1, S2, S3
     */
    public function run(): void
    {
        $this->command->info('🔄 Memulai DosenStrukturalSeeder...');

        // Validasi data yang diperlukan
        $prodi = Prodi::all();
        $kelompokKeahlian = KelompokKeahlian::all();
        
        if ($prodi->isEmpty()) {
            $this->command->error('❌ Data Prodi belum ada! Jalankan ProdiSeeder dulu.');
            return;
        }

        if ($kelompokKeahlian->isEmpty()) {
            $this->command->error('❌ Data Kelompok Keahlian belum ada! Jalankan KelompokKeahlianSeeder dulu.');
            return;
        }

        // Validasi roles
        $dosenRole = Role::where('name', 'dosen')->first();
        $dekanRole = Role::where('name', 'Dekan')->first();
        $wadek1Role = Role::where('name', 'Wadek 1')->first();
        $wadek2Role = Role::where('name', 'Wadek 2')->first();

        if (!$dosenRole) {
            $this->command->error('❌ Role "dosen" belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        if (!$dekanRole || !$wadek1Role || !$wadek2Role) {
            $this->command->error('❌ Role struktural (Dekan/Wadek 1/Wadek 2) belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        // Data 3 dosen struktural
        $dosenData = [
            // 1. Dosen + Dekan
            [
                'nama_lengkap' => 'Hendra Gunawan',
                'front_title' => 'Prof. Dr. Ir.',
                'back_title' => 'M.T',
                'nip' => '197503121998031001',
                'kode_dosen' => 'DSN101',
                'jabatan' => 'Guru Besar',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2010-08-15',
                'status_dosen' => 'Aktif',
                'username' => 'hendragunawan',
                'password' => 'password123',
                'roles' => ['dosen', 'Dekan'],
                'pendidikan' => [
                    [
                        'jenjang' => 'S1',
                        'nama_universitas' => 'Institut Teknologi Bandung',
                        'prodi_pendidikan' => 'Teknik Informatika',
                        'tanggal_lulus' => '1998-06-20',
                    ],
                    [
                        'jenjang' => 'S2',
                        'nama_universitas' => 'Institut Teknologi Bandung',
                        'prodi_pendidikan' => 'Teknik Informatika',
                        'tanggal_lulus' => '2002-09-15',
                    ],
                    [
                        'jenjang' => 'S3',
                        'nama_universitas' => 'Universitas Indonesia',
                        'prodi_pendidikan' => 'Ilmu Komputer',
                        'tanggal_lulus' => '2008-12-10',
                    ],
                ],
            ],
            
            // 2. Dosen + Wadek 1
            [
                'nama_lengkap' => 'Farida Susanti',
                'front_title' => 'Dr.',
                'back_title' => 'M.Kom',
                'nip' => '198005152005042001',
                'kode_dosen' => 'DSN102',
                'jabatan' => 'Lektor Kepala',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2015-03-22',
                'status_dosen' => 'Aktif',
                'username' => 'faridasusanti',
                'password' => 'password123',
                'roles' => ['dosen', 'Wadek 1'],
                'pendidikan' => [
                    [
                        'jenjang' => 'S1',
                        'nama_universitas' => 'Universitas Gadjah Mada',
                        'prodi_pendidikan' => 'Ilmu Komputer',
                        'tanggal_lulus' => '2002-07-25',
                    ],
                    [
                        'jenjang' => 'S2',
                        'nama_universitas' => 'Universitas Indonesia',
                        'prodi_pendidikan' => 'Teknologi Informasi',
                        'tanggal_lulus' => '2007-08-30',
                    ],
                    [
                        'jenjang' => 'S3',
                        'nama_universitas' => 'Institut Teknologi Bandung',
                        'prodi_pendidikan' => 'Informatika',
                        'tanggal_lulus' => '2013-11-18',
                    ],
                ],
            ],
            
            // 3. Dosen + Wadek 2
            [
                'nama_lengkap' => 'Rudi Hartono',
                'front_title' => 'Dr.',
                'back_title' => 'M.T',
                'nip' => '198202082006041002',
                'kode_dosen' => 'DSN103',
                'jabatan' => 'Lektor Kepala',
                'status_pegawai' => 'Tetap',
                'pendidikan_terakhir' => 'S3',
                'sertifikasi_dosen' => true,
                'tanggal_serdos' => '2016-05-10',
                'status_dosen' => 'Aktif',
                'username' => 'rudihartono',
                'password' => 'password123',
                'roles' => ['dosen', 'Wadek 2'],
                'pendidikan' => [
                    [
                        'jenjang' => 'S1',
                        'nama_universitas' => 'Universitas Brawijaya',
                        'prodi_pendidikan' => 'Teknik Informatika',
                        'tanggal_lulus' => '2004-08-12',
                    ],
                    [
                        'jenjang' => 'S2',
                        'nama_universitas' => 'Institut Teknologi Sepuluh Nopember',
                        'prodi_pendidikan' => 'Teknik Informatika',
                        'tanggal_lulus' => '2009-07-20',
                    ],
                    [
                        'jenjang' => 'S3',
                        'nama_universitas' => 'Universitas Gadjah Mada',
                        'prodi_pendidikan' => 'Ilmu Komputer',
                        'tanggal_lulus' => '2015-12-05',
                    ],
                ],
            ],
        ];

        foreach ($dosenData as $data) {
            DB::beginTransaction();
            
            try {
                // Pilih prodi dan kelompok keahlian secara random
                $selectedProdi = $prodi->random();
                $selectedKelompok = $kelompokKeahlian->random();

                // 1. Create User
                $user = User::create([
                    'fakultas_id' => $selectedProdi->fakultas_id,
                    'prodi_id' => $selectedProdi->id,
                    'role_id' => $dosenRole->id, // Role utama: dosen
                    'nama_lengkap' => $data['nama_lengkap'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                ]);

                // 2. Assign Multi-Role (Dosen + Struktural)
                foreach ($data['roles'] as $roleName) {
                    $user->assignRole($roleName);
                }

                // 3. Create Dosen
                $dosen = Dosen::create([
                    'user_id' => $user->id,
                    'prodi_id' => $selectedProdi->id,
                    'kelompok_keahlian_id' => $selectedKelompok->id,
                    'front_title' => $data['front_title'],
                    'nama_lengkap' => $data['nama_lengkap'],
                    'back_title' => $data['back_title'],
                    'jabatan' => $data['jabatan'],
                    'nip' => $data['nip'],
                    'kode_dosen' => $data['kode_dosen'],
                    'status_pegawai' => $data['status_pegawai'],
                    'pendidikan_terakhir' => $data['pendidikan_terakhir'],
                    'sertifikasi_dosen' => $data['sertifikasi_dosen'],
                    'tanggal_serdos' => $data['tanggal_serdos'],
                    'foto_profil' => null,
                    'status_dosen' => $data['status_dosen'],
                ]);

                // 4. Create Riwayat Pendidikan (S1, S2, S3)
                foreach ($data['pendidikan'] as $pendidikan) {
                    RiwayatPendidikanDosen::create([
                        'dosen_id' => $dosen->id,
                        'jenjang' => $pendidikan['jenjang'],
                        'nama_universitas' => $pendidikan['nama_universitas'],
                        'prodi_pendidikan' => $pendidikan['prodi_pendidikan'],
                        'tanggal_lulus' => $pendidikan['tanggal_lulus'],
                        'ijazah' => null,
                        'transkrip_nilai' => null,
                    ]);
                }

                DB::commit();

                $rolesStr = implode(' + ', $data['roles']);
                $this->command->info("✅ {$data['front_title']} {$data['nama_lengkap']}, {$data['back_title']}");
                $this->command->info("   👤 Username: {$data['username']} | Roles: {$rolesStr}");
                $this->command->info("   🎓 Pendidikan: S1 → S2 → S3 (lengkap)");
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("❌ Gagal menambahkan {$data['nama_lengkap']}: {$e->getMessage()}");
            }
        }

        $this->command->info("\n🎉 DosenStrukturalSeeder selesai!");
        $this->command->info("📊 Total: 3 dosen struktural dengan multi-role");
        $this->command->info("   - 1 Dosen + Dekan (S3, Guru Besar)");
        $this->command->info("   - 1 Dosen + Wadek 1 (S3, Lektor Kepala)");
        $this->command->info("   - 1 Dosen + Wadek 2 (S3, Lektor Kepala)");
        $this->command->info("   - Semua sudah sertifikasi dosen");
        $this->command->info("   - Riwayat pendidikan lengkap (S1, S2, S3)");
    }
}
