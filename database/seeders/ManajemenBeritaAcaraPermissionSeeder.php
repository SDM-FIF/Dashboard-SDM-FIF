<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenBeritaAcaraPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Rekrutasi Dosen
     * Nama Sub Modul: Berita Acara
     * 
     * Permissions:
     * - berita-acara.all       : Akses penuh ke semua fitur Berita Acara
     * - berita-acara.access    : Bisa mengakses halaman berita acara (termasuk download)
     * - berita-acara.submit    : Bisa submit berita acara
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → Access only (read-only monitoring, tidak bisa submit)
     * 👨‍🏫 Dosen Penguji 1 → Access & Submit (dengan dynamic check: hanya untuk jadwal yang dia jadi penguji 1)
     * 👨‍🏫 Dosen Penguji 2 → NO ACCESS
     * 👨‍🏫 Dosen Penguji 3 → NO ACCESS
     * 👨‍🏫 Dosen → NO ACCESS
     * 👤 User Biasa → NO ACCESS
     * 🧑‍💼 TPA → NO ACCESS
     * 
     * NOTES:
     * - Hanya Dosen Penguji 1 untuk jadwal tertentu yang bisa access & submit
     * - Super Admin hanya bisa view, tidak bisa submit (sweetalert warning)
     * - Download berita acara gabung dengan access permission
     * - Setelah berita acara di-submit, penilaian tidak bisa di-edit lagi
     */
    public function run(): void
    {
        echo "\n🚀 Starting Berita Acara Permission Seeder...\n";
        echo "========================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'berita-acara.all',
                'description' => 'Akses penuh ke semua fitur Berita Acara'
            ],
            [
                'name' => 'berita-acara.access',
                'description' => 'Bisa mengakses halaman berita acara (termasuk download)'
            ],
            [
                'name' => 'berita-acara.submit',
                'description' => 'Bisa submit berita acara'
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

        // SUPER ADMIN - Access only (read-only monitoring)
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'berita-acara.access',
            ]);
            echo "  👑 Super Admin → Access only (read-only monitoring, tidak bisa submit)\n";
        }

        // DOSEN PENGUJI 1 - Access & Submit
        if ($dosenPenguji1) {
            $dosenPenguji1->givePermissionTo([
                'berita-acara.access',
                'berita-acara.submit',
            ]);
            echo "  👨‍🏫 Dosen Penguji 1 → Access & Submit (dengan dynamic check)\n";
        }

        // OTHER ROLES - NO ACCESS
        echo "\n  🚫 Other Roles → NO ACCESS:\n";
        if ($dosenPenguji2) {
            echo "     👨‍🏫 Dosen Penguji 2 → NO ACCESS ❌\n";
        }
        if ($dosenPenguji3) {
            echo "     👨‍🏫 Dosen Penguji 3 → NO ACCESS ❌\n";
        }
        if ($dosen) {
            echo "     👨‍🏫 Dosen → NO ACCESS ❌\n";
        }
        if ($userBiasa) {
            echo "     👤 User Biasa → NO ACCESS ❌\n";
        }
        if ($tpa) {
            echo "     🧑‍💼 TPA → NO ACCESS ❌\n";
        }

        echo "\n========================================================\n";
        echo "✅ Berita Acara Permissions created & assigned!\n";
        echo "========================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: Access only (monitoring)\n";
        echo "  • Dosen Penguji 1: Access & Submit\n";
        echo "  • Other Roles: No access\n\n";
    }
}
