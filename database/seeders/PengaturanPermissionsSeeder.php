<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PengaturanPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Konfigurasi Sistem Permissions
        $konfigurasiSistemPermissions = [
            'konfigurasi-sistem.all',
            'konfigurasi-sistem.view',
            'konfigurasi-sistem.create',
            'konfigurasi-sistem.edit',
            'konfigurasi-sistem.delete',
        ];

        foreach ($konfigurasiSistemPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // User Management Permissions
        $userManagementPermissions = [
            'user-management.all',
            'user-management.view',
            'user-management.create',
            'user-management.edit',
            'user-management.delete',
        ];

        foreach ($userManagementPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $allPermissions = array_merge($konfigurasiSistemPermissions, $userManagementPermissions);
            $superAdmin->givePermissionTo($allPermissions);
            $this->command->info('✓ Permissions assigned to Super Admin role');
        }

        $this->command->info('✓ Pengaturan permissions created successfully');
    }
}
