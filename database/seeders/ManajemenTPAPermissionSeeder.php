<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenTPAPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * - kelola-data-tpa.all      : Akses penuh ke semua fitur Kelola Data TPA
     * - kelola-data-tpa.view     : Dapat mengakses menu Manajemen TPA - Kelola Data
     * - kelola-data-tpa.create   : Dapat menambah data TPA baru
     * - kelola-data-tpa.detail   : Dapat melihat detail data TPA
     * - kelola-data-tpa.edit     : Dapat mengedit data TPA
     * - kelola-data-tpa.delete   : Dapat menghapus data TPA
     * 
     * ROLE ASSIGNMENTS:
     * - Super Admin → All permissions
     * - Other roles → View & Detail only (no create/edit/delete buttons)
     */
    public function run(): void
    {
        echo "\n🎓 Creating Manajemen TPA - Kelola Data Permissions...\n\n";

        // Define permissions array
        $permissions = [
            [
                'name' => 'kelola-data-tpa.all',
                'description' => 'Akses penuh ke semua fitur Kelola Data TPA',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelola-data-tpa.view',
                'description' => 'Dapat mengakses menu Manajemen TPA - Kelola Data',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelola-data-tpa.create',
                'description' => 'Dapat menambah data TPA baru',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelola-data-tpa.detail',
                'description' => 'Dapat melihat detail data TPA',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelola-data-tpa.edit',
                'description' => 'Dapat mengedit data TPA',
                'guard_name' => 'web'
            ],
            [
                'name' => 'kelola-data-tpa.delete',
                'description' => 'Dapat menghapus data TPA',
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

        // Get Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();

        // Assign all permissions to Super Admin
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'kelola-data-tpa.all',
                'kelola-data-tpa.view',
                'kelola-data-tpa.create',
                'kelola-data-tpa.detail',
                'kelola-data-tpa.edit',
                'kelola-data-tpa.delete',
            ]);
            echo "  👑 Super Admin → All permissions (view, create, detail, edit, delete)\n";
        }

        // Get other roles
        $otherRoles = [
            'dosen' => '👨‍🏫 Dosen',
            'Dosen Penguji 1' => '👨‍🏫 Dosen Penguji 1',
            'Dosen Penguji 2' => '👨‍🏫 Dosen Penguji 2',
            'Dosen Penguji 3' => '👨‍🏫 Dosen Penguji 3',
            'User Biasa' => '👤 User Biasa',
            'tpa' => '👨‍💼 TPA',
        ];

        // Assign view & detail only to other roles
        foreach ($otherRoles as $roleName => $displayName) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $role->givePermissionTo([
                    'kelola-data-tpa.view',
                    'kelola-data-tpa.detail',
                ]);
                echo "  {$displayName} → View & Detail only\n";
            }
        }

        echo "\n=============================================================\n";
        echo "✅ Manajemen TPA - Kelola Data Permissions created & assigned!\n";
        echo "=============================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "   • kelola-data-tpa.all → Super Admin only\n";
        echo "   • kelola-data-tpa.view → All roles ✅\n";
        echo "   • kelola-data-tpa.detail → All roles ✅\n";
        echo "   • kelola-data-tpa.create → Super Admin only\n";
        echo "   • kelola-data-tpa.edit → Super Admin only\n";
        echo "   • kelola-data-tpa.delete → Super Admin only\n\n";
    }
}
