<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenPenilaianCalonDosenPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Rekrutasi Dosen
     * Nama Sub Modul: Penilaian Calon Dosen
     * 
     * Permissions:
     * - penilaian-dosen.all       : Akses penuh ke semua fitur Penilaian Calon Dosen
     * - penilaian-dosen.access    : Bisa mengakses halaman penilaian calon dosen
     * - penilaian-dosen.submit    : Bisa submit penilaian calon dosen
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → Access only (read-only monitoring, tidak bisa submit)
     * 👨‍🏫 Dosen Penguji 1 → Access & Submit (dengan dynamic check: hanya untuk jadwal yang dia jadi penguji)
     * 👨‍🏫 Dosen Penguji 2 → Access & Submit (dengan dynamic check: hanya untuk jadwal yang dia jadi penguji)
     * 👨‍🏫 Dosen Penguji 3 → Access & Submit (dengan dynamic check: hanya untuk jadwal yang dia jadi penguji)
     * 👨‍🏫 Dosen → NO ACCESS
     * 👤 User Biasa → NO ACCESS
     * 🧑‍💼 TPA → NO ACCESS
     * 
     * NOTES:
     * - Icon penilaian di list jadwal hanya muncul jika user adalah penguji di jadwal tersebut
     * - Penilaian bisa di-edit selama berita acara belum di-submit oleh Dosen Penguji 1
     * - Super Admin hanya bisa view, tidak bisa submit (sweetalert warning)
     */
    public function run(): void
    {
        echo "\n🚀 Starting Penilaian Calon Dosen Permission Seeder...\n";
        echo "========================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'penilaian-dosen.all',
                'description' => 'Akses penuh ke semua fitur Penilaian Calon Dosen'
            ],
            [
                'name' => 'penilaian-dosen.access',
                'description' => 'Bisa mengakses halaman penilaian calon dosen'
            ],
            [
                'name' => 'penilaian-dosen.submit',
                'description' => 'Bisa submit penilaian calon dosen'
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
                'penilaian-dosen.access',
            ]);
            echo "  👑 Super Admin → Access only (read-only monitoring, tidak bisa submit)\n";
        }

        // DOSEN PENGUJI 1/2/3 - Access & Submit
        $pengujiRoles = [
            ['role' => $dosenPenguji1, 'name' => 'Dosen Penguji 1'],
            ['role' => $dosenPenguji2, 'name' => 'Dosen Penguji 2'],
            ['role' => $dosenPenguji3, 'name' => 'Dosen Penguji 3'],
        ];

        foreach ($pengujiRoles as $item) {
            if ($item['role']) {
                $item['role']->givePermissionTo([
                    'penilaian-dosen.access',
                    'penilaian-dosen.submit',
                ]);
                echo "  👨‍🏫 {$item['name']} → Access & Submit (dengan dynamic check)\n";
            }
        }

        // OTHER ROLES - NO ACCESS
        echo "\n  🚫 Other Roles → NO ACCESS:\n";
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
        echo "✅ Penilaian Calon Dosen Permissions created & assigned!\n";
        echo "========================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: Access only (monitoring)\n";
        echo "  • Dosen Penguji 1/2/3: Access & Submit\n";
        echo "  • Other Roles: No access\n\n";
    }
}
