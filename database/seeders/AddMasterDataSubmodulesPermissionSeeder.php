<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddMasterDataSubmodulesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define permissions for Tahun Ajaran
        $tahunAjarPermissions = [
            'master-data-tahun-ajar.all',
            'master-data-tahun-ajar.view',
            'master-data-tahun-ajar.detail',
            'master-data-tahun-ajar.create',
            'master-data-tahun-ajar.edit',
            'master-data-tahun-ajar.delete',
        ];

        // 2. Define permissions for Kelompok Keahlian
        $kelompokKeahlianPermissions = [
            'master-data-kelompok-keahlian.all',
            'master-data-kelompok-keahlian.view',
            'master-data-kelompok-keahlian.detail',
            'master-data-kelompok-keahlian.create',
            'master-data-kelompok-keahlian.edit',
            'master-data-kelompok-keahlian.delete',
        ];

        $allNewPermissions = array_merge($tahunAjarPermissions, $kelompokKeahlianPermissions);

        foreach ($allNewPermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }

        // 3. Assign permissions to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($allNewPermissions);
        }

        // 4. Assign permissions to Admin Akademik (all view/create/edit/delete)
        $adminAkademik = Role::where('name', 'Admin Akademik')->first();
        if ($adminAkademik) {
            $adminAkademik->givePermissionTo([
                'master-data-tahun-ajar.view',
                'master-data-tahun-ajar.detail',
                'master-data-tahun-ajar.create',
                'master-data-tahun-ajar.edit',
                'master-data-tahun-ajar.delete',
                'master-data-kelompok-keahlian.view',
                'master-data-kelompok-keahlian.detail',
                'master-data-kelompok-keahlian.create',
                'master-data-kelompok-keahlian.edit',
                'master-data-kelompok-keahlian.delete',
            ]);
        }

        // 5. Assign view permissions to Leadership / Lecturer roles
        $viewRoles = ['Dekan', 'Wadek 1', 'Wadek 2', 'Kaprodi', 'Ketua KK', 'Dosen', 'Tenaga Pendukung Akademik'];
        foreach ($viewRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo([
                    'master-data-tahun-ajar.view',
                    'master-data-kelompok-keahlian.view',
                ]);
            }
        }
    }
}
