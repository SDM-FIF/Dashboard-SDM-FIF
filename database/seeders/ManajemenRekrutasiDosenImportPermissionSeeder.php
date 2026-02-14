<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenRekrutasiDosenImportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Rekrutasi Dosen
     * Nama Sub Modul: Import Rekrutasi Dosen
     * 
     * Permissions:
     * - import-rekrutasi-dosen.all    : Akses penuh ke semua fitur Import Rekrutasi Dosen
     * - import-rekrutasi-dosen.view   : Bisa mengakses menu Import Rekrutasi Dosen (Super Admin ONLY)
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → All permissions (view)
     * 👨‍🏫 Dosen → NO ACCESS ❌ (sub menu tidak muncul)
     * 👨‍🏫 Dosen Penguji 1 → NO ACCESS ❌ (sub menu tidak muncul)
     * 👨‍🏫 Dosen Penguji 2 → NO ACCESS ❌ (sub menu tidak muncul)
     * 👨‍🏫 Dosen Penguji 3 → NO ACCESS ❌ (sub menu tidak muncul)
     * 👤 User Biasa → NO ACCESS ❌ (sub menu tidak muncul)
     * 🧑‍💼 TPA → NO ACCESS ❌ (sub menu tidak muncul)
     */
    public function run(): void
    {
        echo "\n🚀 Starting Import Rekrutasi Dosen Permission Seeder...\n";
        echo "================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'import-rekrutasi-dosen.all',
                'description' => 'Akses penuh ke semua fitur Import Rekrutasi Dosen'
            ],
            [
                'name' => 'import-rekrutasi-dosen.view',
                'description' => 'Bisa mengakses halaman Import Rekrutasi Dosen'
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

        // Get all roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $dosen = Role::where('name', 'dosen')->first();
        $dosenPenguji1 = Role::where('name', 'Dosen Penguji 1')->first();
        $dosenPenguji2 = Role::where('name', 'Dosen Penguji 2')->first();
        $dosenPenguji3 = Role::where('name', 'Dosen Penguji 3')->first();
        $userBiasa = Role::where('name', 'User Biasa')->first();
        $tpa = Role::where('name', 'tpa')->first();

        // SUPER ADMIN - Gets all permissions
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'import-rekrutasi-dosen.view',
            ]);
            echo "  👑 Super Admin → All permissions (view)\n";
        }

        // OTHER ROLES - NO ACCESS (sub menu tidak muncul)
        echo "\n  🚫 Other Roles → NO ACCESS:\n";
        if ($dosen) {
            echo "     👨‍🏫 Dosen → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }
        if ($dosenPenguji1) {
            echo "     👨‍🏫 Dosen Penguji 1 → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }
        if ($dosenPenguji2) {
            echo "     👨‍🏫 Dosen Penguji 2 → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }
        if ($dosenPenguji3) {
            echo "     👨‍🏫 Dosen Penguji 3 → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }
        if ($userBiasa) {
            echo "     👤 User Biasa → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }
        if ($tpa) {
            echo "     🧑‍💼 TPA → NO ACCESS ❌ (sub menu tidak muncul)\n";
        }

        echo "\n================================================\n";
        echo "✅ Import Rekrutasi Dosen Permissions created & assigned!\n";
        echo "================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: Can access Import Rekrutasi Dosen\n";
        echo "  • Other Roles: Cannot access (sub menu hidden)\n\n";
    }
}
