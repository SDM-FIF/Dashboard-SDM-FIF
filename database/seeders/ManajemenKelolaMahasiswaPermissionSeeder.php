<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManajemenKelolaMahasiswaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * PERMISSION STRUCTURE:
     * =====================
     * Modul Parent: Manajemen Mahasiswa
     * Nama Sub Modul: Kelola Data
     * 
     * Permissions:
     * - kelola-data-mahasiswa.all     : Akses penuh ke semua fitur Kelola Data Mahasiswa
     * - kelola-data-mahasiswa.view    : Bisa mengakses menu Manajemen Mahasiswa - Kelola Data
     * - kelola-data-mahasiswa.create  : Bisa menambah data mahasiswa (button Tambah Data)
     * - kelola-data-mahasiswa.detail  : Bisa melihat detail data mahasiswa (icon detail)
     * - kelola-data-mahasiswa.edit    : Bisa mengedit data mahasiswa (icon edit)
     * - kelola-data-mahasiswa.delete  : Bisa menghapus data mahasiswa (icon delete)
     * 
     * ROLE ASSIGNMENT:
     * ================
     * 👑 Super Admin → All permissions (view, create, detail, edit, delete)
     * 👨‍🏫 Dosen → View & Detail only
     * 👨‍🏫 Dosen Penguji 1/2/3 → View & Detail only
     * 👤 User Biasa → View & Detail only
     * 🧑‍💼 TPA → View & Detail only
     */
    public function run(): void
    {
        echo "\n🚀 Starting Kelola Data Mahasiswa Permission Seeder...\n";
        echo "========================================================\n\n";

        // Define permissions
        $permissions = [
            [
                'name' => 'kelola-data-mahasiswa.all',
                'description' => 'Akses penuh ke semua fitur Kelola Data Mahasiswa'
            ],
            [
                'name' => 'kelola-data-mahasiswa.view',
                'description' => 'Bisa mengakses menu Manajemen Mahasiswa - Kelola Data'
            ],
            [
                'name' => 'kelola-data-mahasiswa.create',
                'description' => 'Bisa menambah data mahasiswa (button Tambah Data)'
            ],
            [
                'name' => 'kelola-data-mahasiswa.detail',
                'description' => 'Bisa melihat detail data mahasiswa (icon detail)'
            ],
            [
                'name' => 'kelola-data-mahasiswa.edit',
                'description' => 'Bisa mengedit data mahasiswa (icon edit)'
            ],
            [
                'name' => 'kelola-data-mahasiswa.delete',
                'description' => 'Bisa menghapus data mahasiswa (icon delete)'
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

        // SUPER ADMIN - All permissions
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'kelola-data-mahasiswa.all',
                'kelola-data-mahasiswa.view',
                'kelola-data-mahasiswa.create',
                'kelola-data-mahasiswa.detail',
                'kelola-data-mahasiswa.edit',
                'kelola-data-mahasiswa.delete',
            ]);
            echo "  👑 Super Admin → All permissions (view, create, detail, edit, delete)\n";
        }

        // ALL OTHER ROLES - View & Detail only
        $otherRoles = [
            ['role' => $dosen, 'name' => 'Dosen'],
            ['role' => $dosenPenguji1, 'name' => 'Dosen Penguji 1'],
            ['role' => $dosenPenguji2, 'name' => 'Dosen Penguji 2'],
            ['role' => $dosenPenguji3, 'name' => 'Dosen Penguji 3'],
            ['role' => $userBiasa, 'name' => 'User Biasa'],
            ['role' => $tpa, 'name' => 'TPA'],
        ];

        foreach ($otherRoles as $item) {
            if ($item['role']) {
                $item['role']->givePermissionTo([
                    'kelola-data-mahasiswa.view',
                    'kelola-data-mahasiswa.detail',
                ]);
                echo "  👥 {$item['name']} → View & Detail only\n";
            }
        }

        echo "\n========================================================\n";
        echo "✅ Kelola Data Mahasiswa Permissions created & assigned!\n";
        echo "========================================================\n\n";

        echo "📊 Permission Summary:\n";
        echo "  • Total Permissions: " . count($permissions) . "\n";
        echo "  • Super Admin: All permissions\n";
        echo "  • Other Roles: View & Detail only (no create/edit/delete)\n\n";
    }
}
