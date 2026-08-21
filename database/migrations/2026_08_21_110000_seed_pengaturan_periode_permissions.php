<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'pengaturan-periode.all',
            'pengaturan-periode.view',
            'pengaturan-periode.create',
            'pengaturan-periode.edit',
            'pengaturan-periode.delete',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Assign to Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clean up permissions
        $permissions = [
            'pengaturan-periode.all',
            'pengaturan-periode.view',
            'pengaturan-periode.create',
            'pengaturan-periode.edit',
            'pengaturan-periode.delete',
        ];

        foreach ($permissions as $permName) {
            Permission::where('name', $permName)->delete();
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
