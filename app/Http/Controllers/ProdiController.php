<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('master-data-prodi.view');

        $query = Prodi::with('fakultas');

        if ($request->has('search')) {
            $query->where('nama_prodi', 'like', '%' . $request->search . '%');
        }

        $prodi = $query->paginate(10);
        return view('master-data.prodi.index', compact('prodi'));
    }

    public function create()
    {
        $this->authorize('master-data-prodi.create');

        $fakultas = Fakultas::all();
        return view('master-data.prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $this->authorize('master-data-prodi.create');

        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        Prodi::create($request->all());
        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $this->authorize('master-data-prodi.edit');


        $prodi = Prodi::findOrFail($id);

        $fakultas = \App\Models\Fakultas::all();


        return view('master-data.prodi.edit', compact('prodi', 'fakultas'));
    }
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);

        $jumlahDosen = Dosen::where('prodi_id', $id)->count();
        $jumlahMahasiswa = Mahasiswa::where('prodi_id', $id)->count();

        if ($jumlahDosen > 0 || $jumlahMahasiswa > 0) {

            $pesan = [];

            if ($jumlahDosen > 0) {
                $pesan[] = "{$jumlahDosen} dosen";
            }

            if ($jumlahMahasiswa > 0) {
                $pesan[] = "{$jumlahMahasiswa} mahasiswa";
            }

            return redirect()
                ->route('prodi.index')
                ->with(
                    'error',
                    'Tidak bisa menghapus Program Studi karena masih digunakan oleh ' .
                        implode(' dan ', $pesan) . '.'
                );
        }

        $prodi->delete();

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $this->authorize('master-data-prodi.edit');

        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->route('prodi.index')->with('success', 'Data Prodi berhasil diperbarui!');
    }
}
