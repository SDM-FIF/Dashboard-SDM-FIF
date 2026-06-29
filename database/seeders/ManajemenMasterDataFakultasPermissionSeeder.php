<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenMasterDataFakultasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions for Master Data - Data Fakultas
        $permissions = [
            'master-data-fakultas.all',       // Full access (all actions)
            'master-data-fakultas.view',      // Can access and view fakultas data
            'master-data-fakultas.edit',      // Can edit fakultas data
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

        // Super Admin
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'master-data-fakultas.all',
                'master-data-fakultas.view',
                'master-data-fakultas.edit',
            ]);
        }

        // Kabag TU - view only
        if ($kabag) {
            $kabag->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }

        // Kaprodi - view only
        if ($kaprodi) {
            $kaprodi->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }

        // Dekan/Wadek - view only
        if ($dekanWadek) {
            $dekanWadek->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }

        // Dosen Penguji 1 - view only
        if ($dosenPenguji1) {
            $dosenPenguji1->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }

        // Dosen Penguji 2 - view only
        if ($dosenPenguji2) {
            $dosenPenguji2->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }

        // Dosen Penguji 3 - view only
        if ($dosenPenguji3) {
            $dosenPenguji3->givePermissionTo([
                'master-data-fakultas.view',
            ]);
        }
    }
}
