<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Note: Permissions akan dibuat oleh DashboardPermissionSeeder
        // Disini hanya create roles tanpa assign permission lama yang sudah dihapus
        
        // Create Super Admin role safely
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Create Dosen role safely
        $dosenRole = Role::firstOrCreate(['name' => 'dosen']);

        // Create Dosen Penguji roles
        $dosenPenguji1 = Role::firstOrCreate(['name' => 'Dosen Penguji 1']);
        $dosenPenguji2 = Role::firstOrCreate(['name' => 'Dosen Penguji 2']);
        $dosenPenguji3 = Role::firstOrCreate(['name' => 'Dosen Penguji 3']);

        // Create Structural roles (Dekan, Wadek 1, Wadek 2)
        $dekan = Role::firstOrCreate(['name' => 'Dekan']);
        $wadek1 = Role::firstOrCreate(['name' => 'Wadek 1']);
        $wadek2 = Role::firstOrCreate(['name' => 'Wadek 2']);

        // Create User Biasa role
        $userBiasa = Role::firstOrCreate(['name' => 'User Biasa']);

        // Create TPA role
        $tpaRole = Role::firstOrCreate(['name' => 'tpa']);

        $this->command->info('✅ Roles berhasil dibuat atau diperbarui!');
        $this->command->info('ℹ️  Permissions akan di-assign oleh DashboardPermissionSeeder');
    }
}
