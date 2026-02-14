<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenDosenImportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * - import-data-dosen.all    : Akses penuh ke semua fitur Import Data Dosen
     * - import-data-dosen.view   : Dapat mengakses menu Manajemen Dosen - Import Data
     * 
     * ROLE ASSIGNMENTS:
     * - Super Admin → All permissions (view)
     * - Other roles → NO ACCESS (sub menu tidak muncul)
     */
    public function run(): void
    {
        echo "\n🎓 Creating Manajemen Dosen - Import Data Permissions...\n\n";

        // Define permissions array
        $permissions = [
            [
                'name' => 'import-data-dosen.all',
                'description' => 'Akses penuh ke semua fitur Import Data Dosen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'import-data-dosen.view',
                'description' => 'Dapat mengakses menu Manajemen Dosen - Import Data',
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

        // Get roles
        $superAdmin = Role::where('name', 'Super Admin')->first();

        // Assign permissions ONLY to Super Admin
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'import-data-dosen.all',
                'import-data-dosen.view',
            ]);
            echo "  👑 Super Admin → All permissions (view)\n";
        }

        // List other roles that DO NOT get access
        $otherRoles = [
            'dosen' => '👨‍🏫 Dosen',
            'Dosen Penguji 1' => '👨‍🏫 Dosen Penguji 1',
            'Dosen Penguji 2' => '👨‍🏫 Dosen Penguji 2',
            'Dosen Penguji 3' => '👨‍🏫 Dosen Penguji 3',
            'User Biasa' => '👤 User Biasa',
            'tpa' => '👨‍💼 TPA',
        ];

        foreach ($otherRoles as $roleName => $displayName) {
            echo "  {$displayName} → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }

        echo "\n=============================================================\n";
        echo "✅ Manajemen Dosen - Import Data Permissions created & assigned!\n";
        echo "=============================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "   • import-data-dosen.all → Super Admin only\n";
        echo "   • import-data-dosen.view → Super Admin only ✅\n";
        echo "   • Other roles → NO ACCESS ❌\n\n";
    }
}
