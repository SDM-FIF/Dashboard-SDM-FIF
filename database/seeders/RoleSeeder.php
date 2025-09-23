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

        // Create permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage fakultas',
            'manage prodi',
            'manage dosen',
            'manage mahasiswa',
            'manage kompetisi',
            'manage rekrutasi',
            'view reports',
            'manage system'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Super Admin role
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create Dosen role ← TAMBAH INI
        $dosenRole = Role::create(['name' => 'dosen']);
        $dosenRole->givePermissionTo([
            'view dashboard',
            'manage mahasiswa',
            'manage kompetisi'
        ]);

        $this->command->info('✅ Roles dan permissions berhasil dibuat!');
    }
}