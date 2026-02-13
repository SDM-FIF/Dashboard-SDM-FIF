<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('pengaturan')->with('success', 'Role berhasil ditambahkan.');
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
}
