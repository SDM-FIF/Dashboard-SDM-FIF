<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenJadwalPengujianPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Rekrutasi Dosen
     * Nama Sub Modul: Jadwal Pengujian Dosen
     * 
     * Permissions:
     * - jadwal-pengujian.all      : Akses penuh ke semua fitur Jadwal Pengujian
     * - jadwal-pengujian.view     : Bisa melihat list jadwal pengujian
     * - jadwal-pengujian.detail   : Bisa melihat detail jadwal pengujian
     * - jadwal-pengujian.create   : Bisa membuat jadwal pengujian baru
     * - jadwal-pengujian.edit     : Bisa mengedit jadwal pengujian
     * - jadwal-pengujian.delete   : Bisa menghapus jadwal pengujian
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → All permissions (view, detail, create, edit, delete)
     * 👨‍🏫 Dosen → View & Detail only
     * 👨‍🏫 Dosen Penguji 1 → View & Detail only
     * 👨‍🏫 Dosen Penguji 2 → View & Detail only
     * 👨‍🏫 Dosen Penguji 3 → View & Detail only
     * 👤 User Biasa → View & Detail only
     * 🧑‍💼 TPA → View & Detail only
     */
    public function run(): void
    {
        echo "\n🚀 Starting Jadwal Pengujian Dosen Permission Seeder...\n";
        echo "========================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'jadwal-pengujian.all',
                'description' => 'Akses penuh ke semua fitur Jadwal Pengujian Dosen'
            ],
            [
                'name' => 'jadwal-pengujian.view',
                'description' => 'Bisa melihat list jadwal pengujian'
            ],
            [
                'name' => 'jadwal-pengujian.detail',
                'description' => 'Bisa melihat detail jadwal pengujian'
            ],
            [
                'name' => 'jadwal-pengujian.create',
                'description' => 'Bisa membuat jadwal pengujian baru'
            ],
            [
                'name' => 'jadwal-pengujian.edit',
                'description' => 'Bisa mengedit jadwal pengujian'
            ],
            [
                'name' => 'jadwal-pengujian.delete',
                'description' => 'Bisa menghapus jadwal pengujian'
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
                'jadwal-pengujian.view',
                'jadwal-pengujian.detail',
                'jadwal-pengujian.create',
                'jadwal-pengujian.edit',
                'jadwal-pengujian.delete',
            ]);
            echo "  👑 Super Admin → All permissions (view, detail, create, edit, delete)\n";
        }

        // OTHER ROLES - View & Detail only
        $viewDetailRoles = [
            ['role' => $dosen, 'name' => 'Dosen'],
            ['role' => $dosenPenguji1, 'name' => 'Dosen Penguji 1'],
            ['role' => $dosenPenguji2, 'name' => 'Dosen Penguji 2'],
            ['role' => $dosenPenguji3, 'name' => 'Dosen Penguji 3'],
            ['role' => $userBiasa, 'name' => 'User Biasa'],
            ['role' => $tpa, 'name' => 'TPA'],
        ];

        foreach ($viewDetailRoles as $item) {
            if ($item['role']) {
                $item['role']->givePermissionTo([
                    'jadwal-pengujian.view',
                    'jadwal-pengujian.detail',
                ]);
                echo "  👨‍🏫 {$item['name']} → View & Detail only\n";
            }
        }

        echo "\n========================================================\n";
        echo "✅ Jadwal Pengujian Dosen Permissions created & assigned!\n";
        echo "========================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: Full CRUD access\n";
        echo "  • Other Roles: View & Detail only\n\n";
    }
}
