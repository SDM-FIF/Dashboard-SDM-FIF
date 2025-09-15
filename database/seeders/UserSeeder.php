<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        // Buat user Super Admin tanpa fakultas/prodi
        $superAdmin = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'nama_lengkap' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'fakultas_id' => null, // Super Admin tidak punya fakultas
                'prodi_id' => null,    // Super Admin tidak punya prodi
            ]
        );
        
        // Assign role menggunakan Spatie Permission
        if (!$superAdmin->hasRole('Super Admin')) {
            $superAdmin->assignRole('Super Admin');
        }

        $this->command->info('Super Admin user created: username=superadmin, password=password123');
    }
}