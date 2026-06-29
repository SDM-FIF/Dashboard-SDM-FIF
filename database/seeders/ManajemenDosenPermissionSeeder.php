<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManajemenDosenPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎓 Creating Manajemen Dosen Permissions...');
        $this->command->info('');

        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions for Manajemen Dosen - Kelola Data
        $permissions = [
            [
                'name' => 'kelola-data-dosen.all',
                'description' => 'Akses penuh ke semua fitur Kelola Data Dosen'
            ],
            [
                'name' => 'kelola-data-dosen.view',
                'description' => 'Dapat mengakses menu Manajemen Dosen - Kelola Data'
            ],
            [
                'name' => 'kelola-data-dosen.detail',
                'description' => 'Dapat melihat detail data dosen'
            ],
            [
                'name' => 'kelola-data-dosen.create',
                'description' => 'Dapat menambah data dosen baru'
            ],
            [
                'name' => 'kelola-data-dosen.edit',
                'description' => 'Dapat mengedit data dosen'
            ],
            [
                'name' => 'kelola-data-dosen.delete',
                'description' => 'Dapat menghapus data dosen'
            ],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['guard_name' => 'web']
            );
            $this->command->info("  ✅ {$permission['name']} - {$permission['description']}");
        }

        $this->command->info('');
        $this->command->info('📋 Assigning Permissions to Roles...');
        $this->command->info('');

        // Get all roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $dosen = Role::where('name', 'dosen')->first();
        $dosenPenguji1 = Role::where('name', 'Dosen Penguji 1')->first();
        $dosenPenguji2 = Role::where('name', 'Dosen Penguji 2')->first();
        $dosenPenguji3 = Role::where('name', 'Dosen Penguji 3')->first();
        $userBiasa = Role::where('name', 'User Biasa')->first();
        $tpa = Role::where('name', 'tpa')->first();

        // Super Admin - ALL permissions
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'kelola-data-dosen.all',
                'kelola-data-dosen.view',
                'kelola-data-dosen.detail',
                'kelola-data-dosen.create',
                'kelola-data-dosen.edit',
                'kelola-data-dosen.delete',
            ]);
            $this->command->info('  👑 Super Admin → All permissions (view, detail, create, edit, delete)');
        }

        // Dosen role - VIEW & DETAIL only
        if ($dosen) {
            $dosen->givePermissionTo([
                'kelola-data-dosen.view',
                'kelola-data-dosen.detail',
            ]);
            $this->command->info('  👨‍🏫 Dosen → View & Detail only');
        }

        // Dosen Penguji roles - VIEW & DETAIL only
        foreach ([$dosenPenguji1, $dosenPenguji2, $dosenPenguji3] as $index => $dosenPenguji) {
            if ($dosenPenguji) {
                $dosenPenguji->givePermissionTo([
                    'kelola-data-dosen.view',
                    'kelola-data-dosen.detail',
                ]);
                $this->command->info('  👨‍🏫 Dosen Penguji ' . ($index + 1) . ' → View & Detail only');
            }
        }

        // User Biasa - VIEW & DETAIL only
        if ($userBiasa) {
            $userBiasa->givePermissionTo([
                'kelola-data-dosen.view',
                'kelola-data-dosen.detail',
            ]);
            $this->command->info('  👤 User Biasa → View & Detail only');
        }

        // TPA - VIEW & DETAIL only
        if ($tpa) {
            $tpa->givePermissionTo([
                'kelola-data-dosen.view',
                'kelola-data-dosen.detail',
            ]);
            $this->command->info('  👨‍💼 TPA → View & Detail only');
        }

        $this->command->info('');
        $this->command->info('=============================================================');
        $this->command->info('✅ Manajemen Dosen Permissions created & assigned successfully!');
        $this->command->info('=============================================================');
        $this->command->info('');
        $this->command->info('📊 Permission Summary:');
        $this->command->info('   • kelola-data-dosen.all → Super Admin only');
        $this->command->info('   • kelola-data-dosen.view → All roles ✅');
        $this->command->info('   • kelola-data-dosen.detail → All roles ✅');
        $this->command->info('   • kelola-data-dosen.create → Super Admin only');
        $this->command->info('   • kelola-data-dosen.edit → Super Admin only');
        $this->command->info('   • kelola-data-dosen.delete → Super Admin only');
        $this->command->info('');

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
