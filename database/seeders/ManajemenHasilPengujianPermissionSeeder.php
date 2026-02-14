<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenHasilPengujianPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * PERMISSION STRUCTURE:
     * Modul Parent: Rekrutasi Dosen
     * Nama Sub Modul: Hasil Pengujian
     *
     * Permissions:
     * - hasil-pengujian.all  : Akses penuh ke semua fitur Hasil Pengujian
     * - hasil-pengujian.view : Dapat mengakses menu Rekrutasi Dosen - Hasil Pengujian
     *
    * ROLE ASSIGNMENT:
    * - All roles: view + all
     */
    public function run(): void
    {
        echo "\nCreating Hasil Pengujian permissions...\n\n";

        $permissions = [
            [
                'name' => 'hasil-pengujian.all',
                'description' => 'Akses penuh ke semua fitur Hasil Pengujian',
                'guard_name' => 'web'
            ],
            [
                'name' => 'hasil-pengujian.view',
                'description' => 'Dapat mengakses menu Rekrutasi Dosen - Hasil Pengujian',
                'guard_name' => 'web'
            ],
        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                [
                    'name' => $permissionData['name'],
                    'guard_name' => $permissionData['guard_name']
                ]
            );

            echo "  Created: {$permission->name} - {$permissionData['description']}\n";
        }

        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            $role->givePermissionTo([
                'hasil-pengujian.view',
                'hasil-pengujian.all',
            ]);
            echo "\nAssigned hasil-pengujian.view and hasil-pengujian.all to {$role->name}.\n";
        }

        echo "\nHasil Pengujian permissions created and assigned.\n\n";
    }
}
