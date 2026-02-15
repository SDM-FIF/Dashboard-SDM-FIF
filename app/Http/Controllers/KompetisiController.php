<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use Illuminate\Http\Request;

class KompetisiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('master-data-kompetisi.view');
        
        $query = Kompetisi::query();

        if ($request->has('search')) {
            $query->where('nama_kompetisi', 'like', '%' . $request->search . '%');
        }

        $kompetisi = $query->latest('id')->paginate(10);
        return view('master-data.kompetisi.index', compact('kompetisi'));
    }

    public function create()
    {
        $this->authorize('master-data-kompetisi.create');
        
        // Mengambil opsi dari konstanta model
        $jenisOptions = Kompetisi::getJenisOptions();
        return view('master-data.kompetisi.create', compact('jenisOptions'));
    }

    public function store(Request $request)
    {
        $this->authorize('master-data-kompetisi.create');
        
        $request->validate([
            'nama_kompetisi' => 'required|string|max:255',
            'jenis' => 'required',
            'nama_penyelenggara' => 'required',
            'tingkat_kompetisi' => 'required',
            'tanggal_kompetisi' => 'required|date',
        ]);

        Kompetisi::create($request->all());
        return redirect()->route('kompetisi.index')->with('success', 'Data Kompetisi berhasil ditambahkan!');
    }
    public function destroy($id)
    {
        $this->authorize('master-data-kompetisi.delete');
        
        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->delete();

        return redirect()->route('kompetisi.index')
            ->with('success', 'Data Kompetisi berhasil dihapus!');
    }

    public function edit($id)
    {
        $this->authorize('master-data-kompetisi.edit');
        
        $kompetisi = Kompetisi::findOrFail($id);
        $jenisOptions = Kompetisi::getJenisOptions();
        return view('master-data.kompetisi.edit', compact('kompetisi', 'jenisOptions'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('master-data-kompetisi.edit');
        
        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->update($request->all());

        return redirect()->route('kompetisi.index')->with('success', 'Data Kompetisi berhasil diperbarui!');
    }
}