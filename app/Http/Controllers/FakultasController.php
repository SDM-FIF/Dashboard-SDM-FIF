<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Dosen;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        $query = Fakultas::with(['dekan', 'wadek1', 'wadek2']);

        if ($request->filled('search')) {
            $query->where('nama_fakultas', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'nama-az': $query->orderBy('nama_fakultas', 'asc'); break;
            case 'nama-za': $query->orderBy('nama_fakultas', 'desc'); break;
            default: $query->orderBy('id', 'desc'); break;
        }

        $fakultas = $query->paginate(10);
        
        return view('master-data.fakultas.index', compact('fakultas'));
    }

   public function create()
    {
        // Gunakan nama 'dosenList' agar sama dengan file Edit
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();
        return view('master-data.fakultas.create', compact('dosenList'));
    }

    public function edit($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        
        // Deklarasikan variabel $dosenList
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();

        // Pastikan nama di dalam compact sesuai dengan nama variabel di atas (tanpa $)
        return view('master-data.fakultas.edit', compact('fakultas', 'dosenList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fakultas' => 'required',
            'id_dekan'      => 'nullable|exists:dosen,id',
            'id_wadek1'     => 'nullable|exists:dosen,id',
            'id_wadek2'     => 'nullable|exists:dosen,id',
        ]);

        $fakultas = Fakultas::findOrFail($id);

        $fakultas->update([
            'nama_fakultas' => $request->nama_fakultas,
            'dekan_id'      => $request->id_dekan,
            'wadek1_id'     => $request->id_wadek1,
            'wadek2_id'     => $request->id_wadek2,
        ]);

        return redirect()->route('fakultas.index')->with('success', 'Data berhasil diperbarui!');
    }
}