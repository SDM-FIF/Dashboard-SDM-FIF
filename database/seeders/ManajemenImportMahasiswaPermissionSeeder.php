<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenImportMahasiswaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Manajemen Mahasiswa
     * Nama Sub Modul: Import Data
     * 
     * Permissions:
     * - import-data-mahasiswa.all   : Akses penuh ke semua fitur Import Data Mahasiswa
     * - import-data-mahasiswa.view  : Bisa mengakses menu Manajemen Mahasiswa - Import Data
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → All permissions (view)
     * 🚫 Other roles → NO ACCESS (submenu tidak tampil)
     */
    public function run(): void
    {
        echo "\n🚀 Starting Import Data Mahasiswa Permission Seeder...\n";
        echo "========================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'import-data-mahasiswa.all',
                'description' => 'Akses penuh ke semua fitur Import Data Mahasiswa'
            ],
            [
                'name' => 'import-data-mahasiswa.view',
                'description' => 'Bisa mengakses menu Manajemen Mahasiswa - Import Data'
            ],
        ];

        // Create permissions
        echo "📝 Creating permissions...\n";
        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['guard_name' => 'web']
            );
            echo "  ✅ {$permission['name']} - {$permission['description']}\n";
        }

        echo "\n👥 Assigning permissions to roles...\n";
        echo "-----------------------------------\n";

        // Get Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();

        // SUPER ADMIN - All permissions
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'import-data-mahasiswa.all',
                'import-data-mahasiswa.view',
            ]);
            echo "  👑 Super Admin → All permissions (view)\n";
        }

        // ALL OTHER ROLES - NO ACCESS
        echo "\n  🚫 Other Roles → NO ACCESS:\n";
        $otherRoles = [
            'dosen' => '👨‍🏫 Dosen',
            'Dosen Penguji 1' => '👨‍🏫 Dosen Penguji 1',
            'Dosen Penguji 2' => '👨‍🏫 Dosen Penguji 2',
            'Dosen Penguji 3' => '👨‍🏫 Dosen Penguji 3',
            'User Biasa' => '👤 User Biasa',
            'tpa' => '🧑‍💼 TPA',
        ];

        foreach ($otherRoles as $roleName => $displayName) {
            echo "     {$displayName} → NO ACCESS ❌\n";
        }

        echo "\n========================================================\n";
        echo "✅ Import Data Mahasiswa Permissions created & assigned!\n";
        echo "========================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: view permission\n";
        echo "  • Other Roles: No access (submenu tidak tampil)\n\n";
    }
}
