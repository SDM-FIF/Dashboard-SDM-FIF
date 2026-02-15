<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenMasterDataProdiPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions for Master Data - Data Program Studi
        $permissions = [
            'master-data-prodi.all',       // Full access (all actions)
            'master-data-prodi.view',      // Can access and view prodi data
            'master-data-prodi.create',    // Can create new prodi
            'master-data-prodi.edit',      // Can edit prodi data
            'master-data-prodi.delete',    // Can delete prodi
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get all roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $kabag = Role::where('name', 'Kabag TU')->first();
        $kaprodi = Role::where('name', 'Kaprodi')->first();
        $dekanWadek = Role::where('name', 'Dekan/Wadek')->first();
        $dosenPenguji1 = Role::where('name', 'Dosen Penguji 1')->first();
        $dosenPenguji2 = Role::where('name', 'Dosen Penguji 2')->first();
        $dosenPenguji3 = Role::where('name', 'Dosen Penguji 3')->first();

        // Super Admin - full access
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'master-data-prodi.all',
                'master-data-prodi.view',
                'master-data-prodi.create',
                'master-data-prodi.edit',
                'master-data-prodi.delete',
            ]);
        }

        // Kabag TU - view only
        if ($kabag) {
            $kabag->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }

        // Kaprodi - view only
        if ($kaprodi) {
            $kaprodi->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }

        // Dekan/Wadek - view only
        if ($dekanWadek) {
            $dekanWadek->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }

        // Dosen Penguji 1 - view only
        if ($dosenPenguji1) {
            $dosenPenguji1->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }

        // Dosen Penguji 2 - view only
        if ($dosenPenguji2) {
            $dosenPenguji2->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }

        // Dosen Penguji 3 - view only
        if ($dosenPenguji3) {
            $dosenPenguji3->givePermissionTo([
                'master-data-prodi.view',
            ]);
        }
    }
}
