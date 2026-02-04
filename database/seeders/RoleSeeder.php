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

        // Create permissions safely
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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Super Admin role safely
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create Dosen role safely
        $dosenRole = Role::firstOrCreate(['name' => 'dosen']);
        $dosenRole->givePermissionTo([
            'view dashboard',
            'manage mahasiswa',
            'manage kompetisi'
        ]);

        // Create Dosen Penguji roles
        $dosenPenguji1 = Role::firstOrCreate(['name' => 'Dosen Penguji 1']);
        $dosenPenguji1->givePermissionTo([
            'view dashboard',
            'manage rekrutasi'
        ]);

        $dosenPenguji2 = Role::firstOrCreate(['name' => 'Dosen Penguji 2']);
        $dosenPenguji2->givePermissionTo([
            'view dashboard',
            'manage rekrutasi'
        ]);

        $dosenPenguji3 = Role::firstOrCreate(['name' => 'Dosen Penguji 3']);
        $dosenPenguji3->givePermissionTo([
            'view dashboard',
            'manage rekrutasi'
        ]);

        // Create User Biasa role
        $userBiasa = Role::firstOrCreate(['name' => 'User Biasa']);
        $userBiasa->givePermissionTo([
            'view dashboard'
        ]);

         // ======================
        // TENAGA PENDUKUNG AKADEMIK (TPA)
        // ======================
        $tpaRole = Role::firstOrCreate(['name' => 'tpa']);
        $tpaRole->givePermissionTo([
            'view dashboard',
            'manage mahasiswa',
            'manage dosen',
            'manage kompetisi',
            'view reports'
        ]);

        $this->command->info('✅ Roles dan permissions berhasil dibuat atau diperbarui!');
    }
}
