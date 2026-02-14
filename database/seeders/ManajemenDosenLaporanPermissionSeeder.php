<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenDosenLaporanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * - laporan-data-dosen.all    : Akses penuh ke semua fitur Laporan Dosen
     * - laporan-data-dosen.view   : Dapat mengakses menu Manajemen Dosen - Laporan Dosen
     * 
     * ROLE ASSIGNMENTS:
     * - ALL ROLES → Get view permission (Super Admin, Dosen, Dosen Penguji 1/2/3, User Biasa, TPA)
     */
    public function run(): void
    {
        echo "\n🎓 Creating Manajemen Dosen - Laporan Dosen Permissions...\n\n";

        // Define permissions array
        $permissions = [
            [
                'name' => 'laporan-data-dosen.all',
                'description' => 'Akses penuh ke semua fitur Laporan Dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'laporan-data-dosen.view',
                'description' => 'Dapat mengakses menu Manajemen Dosen - Laporan Dosen',
                'guard_name' => 'web'
            ],
        ];

        // Create permissions
        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                [
                    'name' => $permissionData['name'],
                    'guard_name' => $permissionData['guard_name']
                ]
            );

            echo "  ✅ {$permission->name} - {$permissionData['description']}\n";
        }

        echo "\n📋 Assigning Permissions to Roles...\n\n";

        // Define all roles that get access
        $roles = [
            'Super Admin' => '👑 Super Admin',
            'dosen' => '👨‍🏫 Dosen',
            'Dosen Penguji 1' => '👨‍🏫 Dosen Penguji 1',
            'Dosen Penguji 2' => '👨‍🏫 Dosen Penguji 2',
            'Dosen Penguji 3' => '👨‍🏫 Dosen Penguji 3',
            'User Biasa' => '👤 User Biasa',
            'tpa' => '👨‍💼 TPA',
        ];

        // Assign permissions to ALL roles
        foreach ($roles as $roleName => $displayName) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $role->givePermissionTo([
                    'laporan-data-dosen.all',
                    'laporan-data-dosen.view',
                ]);
                echo "  {$displayName} → All permissions (view) ✅\n";
            }
        }

        echo "\n=============================================================\n";
        echo "✅ Manajemen Dosen - Laporan Dosen Permissions created & assigned!\n";
        echo "=============================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "   • laporan-data-dosen.all → ALL ROLES ✅\n";
        echo "   • laporan-data-dosen.view → ALL ROLES ✅\n\n";
    }
}
