<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoleExport;
use App\Exports\PlottingPermissionExport;
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
            ],
            'Manajemen Dosen' => [
                'kelola-data-dosen' => 'Kelola Data',
                'import-data-dosen' => 'Import Data',
                'laporan-data-dosen' => 'Laporan Dosen',
            ],
            'Manajemen TPA' => [
                'kelola-data-tpa' => 'Kelola Data',
                'import-data-tpa' => 'Import Data',
            ],
            'Rekrutasi Dosen' => [
                'rekrutasi-data-dosen' => 'Data Rekrutasi Dosen',
                'import-rekrutasi-dosen' => 'Import Rekrutasi Dosen',
                'jadwal-pengujian' => 'Jadwal Pengujian Dosen',
                'penilaian-dosen' => 'Penilaian Calon Dosen',
                'berita-acara' => 'Berita Acara',
                'hasil-pengujian' => 'Hasil Pengujian',
            ],
            'Manajemen Mahasiswa' => [
                'kelola-data-mahasiswa' => 'Kelola Data',
                'import-data-mahasiswa' => 'Import Data',
            ],
            'Master Data' => [
                'master-data-fakultas' => 'Data Fakultas',
                'master-data-prodi' => 'Data Program Studi',
                'master-data-kompetisi' => 'Data Kompetisi',
            ]
        ];
        
        // Get permissions for each sub-module
        $permissionData = [];
        foreach ($moduleGroups as $parentModule => $subModules) {
            foreach ($subModules as $key => $label) {
                // Define permission types based on module
                if ($key === 'penilaian-dosen' || $key === 'berita-acara') {
                    // Special handling for penilaian-dosen and berita-acara (access & submit)
                    $permissionTypes = ['all', 'access', 'submit'];
                } elseif ($key === 'hasil-pengujian') {
                    // Hasil Pengujian only needs all + view
                    $permissionTypes = ['all', 'view'];
                } else {
                    // Standard permission types for other modules
                    $permissionTypes = ['all', 'view', 'detail', 'create', 'edit', 'delete'];
                }
                
                $permissions = [];
                foreach ($permissionTypes as $type) {
                    $permission = Permission::where('name', "{$key}.{$type}")->first();
                    
                    if ($permission) {
                        // Custom label for each permission type
                        $typeLabel = match($type) {
                            'all' => 'All',
                            'view' => "Akses View {$label}",
                            'detail' => "Akses Detail {$label}",
                            'create' => "Akses Create {$label}",
                            'edit' => "Akses Edit {$label}",
                            'delete' => "Akses Delete {$label}",
                            'access' => "Akses {$label}",
                            'submit' => "Akses Submit {$label}",
                            default => ucfirst($type)
                        };
                        
                        // Determine if checkbox should be disabled and its state
                        $isDisabled = false;
                        $forceChecked = false;
                        $forceUnchecked = false;
                        
                        // Special logic for Penilaian Calon Dosen
                        if ($key === 'penilaian-dosen') {
                            if (in_array($role->name, ['Dosen Penguji 1', 'Dosen Penguji 2', 'Dosen Penguji 3'])) {
                                // Dosen Penguji 1/2/3: All checked & disabled
                                $isDisabled = true;
                                $forceChecked = true;
                            } elseif ($role->name === 'Super Admin') {
                                // Super Admin: Only 'access' checked, 'submit' unchecked, all disabled
                                $isDisabled = true;
                                if ($type === 'access' || $type === 'all') {
                                    $forceChecked = true;
                                } else {
                                    $forceUnchecked = true;
                                }
                            } else {
                                // Other roles: All unchecked & disabled
                                $isDisabled = true;
                                $forceUnchecked = true;
                            }
                        }
                        
                        // Special logic for Berita Acara
                        if ($key === 'berita-acara') {
                            if ($role->name === 'Dosen Penguji 1') {
                                // Dosen Penguji 1: All checked & disabled
                                $isDisabled = true;
                                $forceChecked = true;
                            } elseif ($role->name === 'Super Admin') {
                                // Super Admin: Only 'access' checked, 'submit' unchecked, all disabled
                                $isDisabled = true;
                                if ($type === 'access' || $type === 'all') {
                                    $forceChecked = true;
                                } else {
                                    $forceUnchecked = true;
                                }
                            } else {
                                // Other roles (including Dosen Penguji 2/3): All unchecked & disabled
                                $isDisabled = true;
                                $forceUnchecked = true;
                            }
                        }

                        // Hasil Pengujian is editable manually (no forced state)
                        
                        $hasPermission = $role->hasPermissionTo($permission->name);
                        if ($forceChecked) {
                            $hasPermission = true;
                        } elseif ($forceUnchecked) {
                            $hasPermission = false;
                        }
                        
                        $permissions[$type] = [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'label' => $typeLabel,
                            'has_permission' => $hasPermission,
                            'is_disabled' => $isDisabled
                        ];
                    }
                }
                
                $permissionData[] = [
                    'parent_module' => $parentModule,
                    'sub_module' => $label,
                    'sub_module_key' => $key,
                    'permissions' => $permissions
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

    /**
     * Export plotting permission to Excel
     */
    public function exportPlottingExcel($roleId)
    {
        $role = Role::findOrFail($roleId);
        $fileName = 'Plotting_Permission_' . str_replace(' ', '_', $role->name) . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new PlottingPermissionExport($roleId), $fileName);
    }

    /**
     * Export plotting permission to CSV
     */
    public function exportPlottingCsv($roleId)
    {
        $role = Role::findOrFail($roleId);
        $fileName = 'Plotting_Permission_' . str_replace(' ', '_', $role->name) . '_' . date('Y-m-d') . '.csv';
        
        return Excel::download(new PlottingPermissionExport($roleId), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Export plotting permission to PDF
     */
    public function exportPlottingPdf($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Prepare data like in plotting method
        $moduleGroups = [
            'Dashboard' => [
                'dashboard-sdm' => 'Dashboard SDM',
                'dashboard-dosen' => 'Dashboard Dosen',
                'dashboard-tpa' => 'Dashboard TPA',
                'dashboard-kompetisi' => 'Dashboard Kompetisi',
            ],
            'Manajemen Dosen' => [
                'kelola-data-dosen' => 'Kelola Data',
                'import-data-dosen' => 'Import Data',
                'laporan-data-dosen' => 'Laporan Dosen',
            ],
            'Manajemen TPA' => [
                'kelola-data-tpa' => 'Kelola Data',
                'import-data-tpa' => 'Import Data',
            ],
            'Rekrutasi Dosen' => [
                'rekrutasi-data-dosen' => 'Data Rekrutasi Dosen',
                'import-rekrutasi-dosen' => 'Import Rekrutasi Dosen',
                'jadwal-pengujian' => 'Jadwal Pengujian Dosen',
                'penilaian-dosen' => 'Penilaian Calon Dosen',
                'berita-acara' => 'Berita Acara',
                'hasil-pengujian' => 'Hasil Pengujian',
            ],
            'Manajemen Mahasiswa' => [
                'kelola-data-mahasiswa' => 'Kelola Data',
                'import-data-mahasiswa' => 'Import Data',
            ],
            'Master Data' => [
                'master-data-fakultas' => 'Data Fakultas',
                'master-data-prodi' => 'Data Program Studi',
                'master-data-kompetisi' => 'Data Kompetisi',
            ]
        ];

        $permissionData = [];
        foreach ($moduleGroups as $parentModule => $subModules) {
            foreach ($subModules as $key => $label) {
                // Define permission types based on module
                if ($key === 'penilaian-dosen' || $key === 'berita-acara') {
                    $permissionTypes = ['all', 'access', 'submit'];
                } elseif ($key === 'hasil-pengujian') {
                    $permissionTypes = ['all', 'view'];
                } else {
                    $permissionTypes = ['all', 'view', 'detail', 'create', 'edit', 'delete'];
                }

                $permissions = [];
                foreach ($permissionTypes as $type) {
                    $permission = Permission::where('name', "{$key}.{$type}")->first();
                    if ($permission) {
                        $hasPermission = $role->hasPermissionTo($permission->name);
                        $permissions[$type] = $hasPermission ? 'Ya' : 'Tidak';
                    }
                }

                $permissionData[] = [
                    'parent_module' => $parentModule,
                    'sub_module' => $label,
                    'permissions' => $permissions
                ];
            }
        }

        $fileName = 'Plotting_Permission_' . str_replace(' ', '_', $role->name) . '_' . date('Y-m-d') . '.pdf';
        
        $pdf = Pdf::loadView('pengaturan.plotting-export-pdf', [
            'role' => $role,
            'permissionData' => $permissionData
        ]);
        
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download($fileName);
    }
}

