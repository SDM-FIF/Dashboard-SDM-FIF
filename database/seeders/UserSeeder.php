<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete existing super admin (jika ada)
        User::where('username', 'superadmin')->delete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil role Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        
        if (!$superAdminRole) {
            $this->command->error('❌ Role "Super Admin" belum ada! Jalankan RoleSeeder dulu.');
            return;
        }

        // Create Super Admin TANPA fakultas dan prodi
        $superAdmin = User::create([
            'fakultas_id' => null, // ✅ Super Admin tidak terikat fakultas
            'prodi_id' => null,    // ✅ Super Admin tidak terikat prodi
            'role_id' => $superAdminRole->id, // ✅ Role Super Admin
            'nama_lengkap' => 'Super Administrator',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
        ]);

        // Assign Super Admin role
        $superAdmin->assignRole('Super Admin');

        $this->command->info('✅ Super Admin berhasil dibuat!');
        $this->command->info('   Username: superadmin');
        $this->command->info('   Password: password123');
        $this->command->info('   Role ID: ' . $superAdminRole->id);
        $this->command->info('   Fakultas: NULL (akses semua fakultas)');
        $this->command->info('   Prodi: NULL (akses semua prodi)');
    }
}