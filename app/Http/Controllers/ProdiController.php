<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prodi::with('fakultas');

        if ($request->has('search')) {
            $query->where('nama_prodi', 'like', '%' . $request->search . '%');
        }

        $prodi = $query->paginate(10);
        return view('master-data.prodi.index', compact('prodi'));
    }

    public function create()
    {
        $fakultas = Fakultas::all();
        return view('master-data.prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        Prodi::create($request->all());
        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // 1. Cari data prodi yang mau diedit
        $prodi = Prodi::findOrFail($id);

        // 2. Ambil SEMUA data fakultas untuk isi dropdown (Ini yang sering terlewat)
        $fakultas = \App\Models\Fakultas::all();

        // 3. Kirim kedua variabel ke view
        return view('master-data.prodi.edit', compact('prodi', 'fakultas'));
    }
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);

        // Opsional: Cek jika ada relasi sebelum hapus (biar gak error database constraint)
        // if($prodi->mahasiswa()->count() > 0) {
        //     return back()->with('error', 'Tidak bisa hapus Prodi yang masih memiliki mahasiswa!');
        // }

        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Program Studi berhasil dihapus!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->route('prodi.index')->with('success', 'Data Prodi berhasil diperbarui!');
    }
}