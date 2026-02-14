<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Dashboard sub-modules with their permissions
        $dashboardSubModules = [
            'dashboard-sdm' => 'Dashboard SDM',
            'dashboard-dosen' => 'Dashboard Dosen',
            'dashboard-tpa' => 'Dashboard TPA',
            'dashboard-kompetisi' => 'Dashboard Kompetisi',
        ];

        // Create permissions for each sub-module
        $createdPermissions = [];
        foreach ($dashboardSubModules as $key => $label) {
            // Create All permission
            $allPermission = Permission::firstOrCreate([
                'name' => "{$key}.all"
            ]);
            $createdPermissions[] = $allPermission;

            // Create View permission
            $viewPermission = Permission::firstOrCreate([
                'name' => "{$key}.view"
            ]);
            $createdPermissions[] = $viewPermission;

            $this->command->info("Created permissions for {$label}: {$key}.all, {$key}.view");
        }

        // Give all Dashboard permissions to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($createdPermissions);
            $this->command->info('✅ Super Admin diberi semua Dashboard permissions');
        }

        // Give only View permissions to other roles (by default everyone can view dashboard)
        $viewPermissions = Permission::where('name', 'like', 'dashboard-%.view')->get();
        
        $otherRoles = Role::whereNotIn('name', ['Super Admin'])->get();
        foreach ($otherRoles as $role) {
            $role->givePermissionTo($viewPermissions);
            $this->command->info("✅ {$role->name} diberi Dashboard view permissions");
        }

        $this->command->info('✅ Dashboard permissions berhasil dibuat dan di-assign!');
    }
}
