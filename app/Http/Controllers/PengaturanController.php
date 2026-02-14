<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoleExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    /**
     * Display the konfigurasi sistem page with roles list
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $roles = Role::when($search, function($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'asc')
            ->get();
        
        return view('pengaturan.konfigurasi-sistem', compact('roles', 'search'));
    }

    /**
     * Store a newly created role in storage
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.max' => 'Nama role tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Role dengan nama "' . $request->name . '" sudah ada. Silakan gunakan nama yang berbeda (contoh: "' . $request->name . ' 2").',
        ]);

        // Create role
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        // Generate username from role name (lowercase, no spaces)
        $username = strtolower(str_replace(' ', '', $request->name));
        
        // Check if username already exists, if yes, append role_id
        $existingUser = User::where('username', $username)->first();
        if ($existingUser) {
            $username = $username . $role->id;
        }

        // Create user with the role
        $user = User::create([
            'fakultas_id' => null,
            'prodi_id' => null,
            'role_id' => $role->id,
            'nama_lengkap' => $request->name,
            'username' => $username,
            'password' => Hash::make('password123'),
        ]);

        // Assign role to user
        $user->assignRole($role->name);

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('pengaturan.plotting', ['roleId' => $role->id])
            ->with('success', "Role '{$role->name}' berhasil ditambahkan. User dengan username '{$username}' telah dibuat. Silakan set hak akses untuk role ini.");
    }

    /**
     * Update the specified role in storage
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('pengaturan')->with('success', 'Role berhasil diupdate.');
    }

    /**
     * Remove the specified role from storage
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deletion of important roles
        if (in_array($role->name, ['Super Admin', 'Admin Akademik', 'Dosen', 'User Biasa'])) {
            return redirect()->route('pengaturan')->with('error', 'Role ini tidak dapat dihapus karena merupakan role sistem.');
        }

        $role->delete();

        return redirect()->route('pengaturan')->with('success', 'Role berhasil dihapus.');
    }

    /**
     * Export role data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search']);
        
        return Excel::download(new RoleExport($filters), 'data-role-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export role data to CSV.
     */
    public function exportCsv(Request $request)
    {
        try {
            $filters = $request->only(['search']);
            $fileName = 'data-role-' . date('Y-m-d-His') . '.csv';

            return Excel::download(
                new RoleExport($filters),
                $fileName,
                \Maatwebsite\Excel\Excel::CSV,
                [
                    'Content-Type' => 'text/csv',
                ]
            );
        } catch (\Exception $e) {
            logger()->error('Export CSV Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Export CSV gagal: ' . $e->getMessage());
        }
    }

    /**
     * Export role data to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Role::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->orderBy('id', 'asc')->get();

        $pdf = Pdf::loadView('pengaturan.export-pdf', compact('roles'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('data-role-' . date('Y-m-d-His') . '.pdf');
    }

    /**
     * Display plotting permission page for a specific role
     */
    public function plotting($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Get all permissions grouped by module
        $permissions = Permission::all();
        
        // Group permissions by module parent
        $moduleGroups = [
            'Dashboard' => [
                'dashboard-sdm' => 'Dashboard SDM',
                'dashboard-dosen' => 'Dashboard Dosen',
                'dashboard-tpa' => 'Dashboard TPA',
                'dashboard-kompetisi' => 'Dashboard Kompetisi',
            ]
        ];
        
        // Get permissions for each sub-module
        $permissionData = [];
        foreach ($moduleGroups as $parentModule => $subModules) {
            foreach ($subModules as $key => $label) {
                $allPermission = Permission::where('name', "{$key}.all")->first();
                $viewPermission = Permission::where('name', "{$key}.view")->first();
                
                $permissionData[] = [
                    'parent_module' => $parentModule,
                    'sub_module' => $label,
                    'sub_module_key' => $key,
                    'permissions' => [
                        'all' => [
                            'id' => $allPermission->id ?? null,
                            'name' => $allPermission->name ?? null,
                            'label' => 'All',
                            'has_permission' => $allPermission ? $role->hasPermissionTo($allPermission->name) : false
                        ],
                        'view' => [
                            'id' => $viewPermission->id ?? null,
                            'name' => $viewPermission->name ?? null,
                            'label' => "Akses View {$label}",
                            'has_permission' => $viewPermission ? $role->hasPermissionTo($viewPermission->name) : false
                        ]
                    ]
                ];
            }
        }
        
        return view('pengaturan.plotting-permission', compact('role', 'permissionData'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Get all permissions from request
        $permissionIds = $request->input('permissions', []);
        
        // Get Permission objects
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        // Sync permissions for this role
        $role->syncPermissions($permissions);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Check if current user is editing their own role
        $currentUserRole = auth()->user()->roles->first();
        $isEditingOwnRole = $currentUserRole && $currentUserRole->id == $roleId;
        
        $message = 'Permissions berhasil diupdate untuk role ' . $role->name . '.';
        if ($isEditingOwnRole) {
            $message .= ' Silakan logout dan login kembali untuk melihat perubahan.';
        }
        
        return redirect()->route('pengaturan.plotting', $roleId)
            ->with('success', $message)
            ->with('is_own_role', $isEditingOwnRole);
    }
}

