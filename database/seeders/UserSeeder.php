<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\KelompokKeahlian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete existing users and dosen
        $userIds = User::whereIn('username', ['superadmin', 'dosenpenguji1', 'dosenpenguji2', 'dosenpenguji3', 'userbiasa'])->pluck('id');
        Dosen::whereIn('user_id', $userIds)->delete();
        User::whereIn('username', ['superadmin', 'dosenpenguji1', 'dosenpenguji2', 'dosenpenguji3', 'userbiasa'])->delete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $dosenPenguji1Role = Role::where('name', 'Dosen Penguji 1')->first();
        $dosenPenguji2Role = Role::where('name', 'Dosen Penguji 2')->first();
        $dosenPenguji3Role = Role::where('name', 'Dosen Penguji 3')->first();
        $userBiasaRole = Role::where('name', 'User Biasa')->first();
        
        if (!$superAdminRole) {
            $this->command->error('❌ Role belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        // Get default fakultas, prodi, kelompok keahlian
        $fakultas = Fakultas::first();
        $prodi = Prodi::first();
        $kelompokKeahlian = KelompokKeahlian::first();

        if (!$fakultas || !$prodi || !$kelompokKeahlian) {
            $this->command->error('❌ Data Fakultas/Prodi/Kelompok Keahlian belum ada! Jalankan seeder yang sesuai dulu.');
            return;
        }

        // Create Super Admin
        $superAdmin = User::create([
            'fakultas_id' => null,
            'prodi_id' => null,
            'role_id' => $superAdminRole->id,
            'nama_lengkap' => 'Super Administrator',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Create Dosen Penguji 1 User
        $dosenPenguji1 = User::create([
            'fakultas_id' => $fakultas->id,
            'prodi_id' => $prodi->id,
            'role_id' => $dosenPenguji1Role->id,
            'nama_lengkap' => 'Dr. Ahmad Wijaya, M.Kom',
            'username' => 'dosenpenguji1',
            'password' => Hash::make('password123'),
        ]);
        $dosenPenguji1->assignRole('Dosen Penguji 1');
        
        // Create Dosen record for Penguji 1
        Dosen::create([
            'user_id' => $dosenPenguji1->id,
            'prodi_id' => $prodi->id,
            'kelompok_keahlian_id' => $kelompokKeahlian->id,
            'front_title' => 'Dr.',
            'nama_lengkap' => 'Ahmad Wijaya',
            'back_title' => 'M.Kom',
            'jabatan' => 'Lektor',
            'nip' => '198501012010121001',
            'kode_dosen' => 'DSN001',
            'status_pegawai' => 'Tetap',
            'pendidikan_terakhir' => 'S3',
        ]);

        // Create Dosen Penguji 2 User
        $dosenPenguji2 = User::create([
            'fakultas_id' => $fakultas->id,
            'prodi_id' => $prodi->id,
            'role_id' => $dosenPenguji2Role->id,
            'nama_lengkap' => 'Prof. Dr. Budi Santoso, M.T',
            'username' => 'dosenpenguji2',
            'password' => Hash::make('password123'),
        ]);
        $dosenPenguji2->assignRole('Dosen Penguji 2');
        
        // Create Dosen record for Penguji 2
        Dosen::create([
            'user_id' => $dosenPenguji2->id,
            'prodi_id' => $prodi->id,
            'kelompok_keahlian_id' => $kelompokKeahlian->id,
            'front_title' => 'Prof. Dr.',
            'nama_lengkap' => 'Budi Santoso',
            'back_title' => 'M.T',
            'jabatan' => 'Guru Besar',
            'nip' => '197803152005011002',
            'kode_dosen' => 'DSN002',
            'status_pegawai' => 'Tetap',
            'pendidikan_terakhir' => 'S3',
        ]);

        // Create Dosen Penguji 3 User
        $dosenPenguji3 = User::create([
            'fakultas_id' => $fakultas->id,
            'prodi_id' => $prodi->id,
            'role_id' => $dosenPenguji3Role->id,
            'nama_lengkap' => 'Dr. Citra Dewi, M.Sc',
            'username' => 'dosenpenguji3',
            'password' => Hash::make('password123'),
        ]);
        $dosenPenguji3->assignRole('Dosen Penguji 3');
        
        // Create Dosen record for Penguji 3
        Dosen::create([
            'user_id' => $dosenPenguji3->id,
            'prodi_id' => $prodi->id,
            'kelompok_keahlian_id' => $kelompokKeahlian->id,
            'front_title' => 'Dr.',
            'nama_lengkap' => 'Citra Dewi',
            'back_title' => 'M.Sc',
            'jabatan' => 'Lektor Kepala',
            'nip' => '198209252008122003',
            'kode_dosen' => 'DSN003',
            'status_pegawai' => 'Tetap',
            'pendidikan_terakhir' => 'S3',
        ]);

        // Create User Biasa
        $userBiasa = User::create([
            'fakultas_id' => null,
            'prodi_id' => null,
            'role_id' => $userBiasaRole->id,
            'nama_lengkap' => 'Andi Pratama',
            'username' => 'userbiasa',
            'password' => Hash::make('password123'),
        ]);
        $userBiasa->assignRole('User Biasa');

        $this->command->info('✅ Users dan Dosen berhasil dibuat!');
        $this->command->info('');
        $this->command->info('=== CREDENTIALS ===');
        $this->command->info('Super Admin:');
        $this->command->info('  Username: superadmin | Password: password123');
        $this->command->info('');
        $this->command->info('Dosen Penguji 1:');
        $this->command->info('  Username: dosenpenguji1 | Password: password123');
        $this->command->info('  NIP: 198501012010121001');
        $this->command->info('');
        $this->command->info('Dosen Penguji 2:');
        $this->command->info('  Username: dosenpenguji2 | Password: password123');
        $this->command->info('  NIP: 197803152005011002');
        $this->command->info('');
        $this->command->info('Dosen Penguji 3:');
        $this->command->info('  Username: dosenpenguji3 | Password: password123');
        $this->command->info('  NIP: 198209252008122003');
        $this->command->info('');
        $this->command->info('User Biasa:');
        $this->command->info('  Username: userbiasa | Password: password123');
    }
}