<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenRekrutasiDosenPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * - rekrutasi-data-dosen.all      : Akses penuh ke semua fitur Data Rekrutasi Dosen
     * - rekrutasi-data-dosen.view     : Dapat mengakses menu Rekrutasi Dosen - Data Rekrutasi Dosen
     * - rekrutasi-data-dosen.create   : Dapat menambah data rekrutasi dosen baru
     * - rekrutasi-data-dosen.detail   : Dapat melihat detail data rekrutasi dosen
     * - rekrutasi-data-dosen.edit     : Dapat mengedit data rekrutasi dosen
     * - rekrutasi-data-dosen.delete   : Dapat menghapus data rekrutasi dosen
     * 
     * ROLE ASSIGNMENTS:
     * - Super Admin → All permissions
     * - Other roles → View & Detail only (no create/edit/delete buttons)
     */
    public function run(): void
    {
        echo "\n🎓 Creating Rekrutasi Dosen - Data Rekrutasi Dosen Permissions...\n\n";

        // Define permissions array
        $permissions = [
            [
                'name' => 'rekrutasi-data-dosen.all',
                'description' => 'Akses penuh ke semua fitur Data Rekrutasi Dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'rekrutasi-data-dosen.view',
                'description' => 'Dapat mengakses menu Rekrutasi Dosen - Data Rekrutasi Dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'rekrutasi-data-dosen.create',
                'description' => 'Dapat menambah data rekrutasi dosen baru',
                'guard_name' => 'web'
            ],
            [
                'name' => 'rekrutasi-data-dosen.detail',
                'description' => 'Dapat melihat detail data rekrutasi dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'rekrutasi-data-dosen.edit',
                'description' => 'Dapat mengedit data rekrutasi dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'rekrutasi-data-dosen.delete',
                'description' => 'Dapat menghapus data rekrutasi dosen',
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
                'rekrutasi-data-dosen.all',
                'rekrutasi-data-dosen.view',
                'rekrutasi-data-dosen.create',
                'rekrutasi-data-dosen.detail',
                'rekrutasi-data-dosen.edit',
                'rekrutasi-data-dosen.delete',
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
                    'rekrutasi-data-dosen.view',
                    'rekrutasi-data-dosen.detail',
                ]);
                echo "  {$displayName} → View & Detail only\n";
            }
        }

        echo "\n=============================================================\n";
        echo "✅ Rekrutasi Dosen - Data Rekrutasi Dosen Permissions created & assigned!\n";
        echo "=============================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "   • rekrutasi-data-dosen.all → Super Admin only\n";
        echo "   • rekrutasi-data-dosen.view → All roles ✅\n";
        echo "   • rekrutasi-data-dosen.detail → All roles ✅\n";
        echo "   • rekrutasi-data-dosen.create → Super Admin only\n";
        echo "   • rekrutasi-data-dosen.edit → Super Admin only\n";
        echo "   • rekrutasi-data-dosen.delete → Super Admin only\n\n";
    }
}
