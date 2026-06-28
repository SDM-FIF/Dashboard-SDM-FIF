<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Prodi;
use App\Models\Mahasiswa;
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
        $prodis = Prodi::all();
        return view('master-data.kompetisi.create', compact('jenisOptions', 'prodis'));
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
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*.nama_lengkap' => 'required|string',
            'mahasiswa.*.nim' => 'required|numeric',
            'mahasiswa.*.prodi_id' => 'required|exists:prodi,id',
            'mahasiswa.*.capaian' => 'required|string',
        ]);

        $kompetisi = Kompetisi::create($request->except('mahasiswa'));

        if ($request->has('mahasiswa') && is_array($request->mahasiswa)) {
            foreach ($request->mahasiswa as $mhs) {
                $mahasiswa = Mahasiswa::updateOrCreate(
                    ['nim' => $mhs['nim']],
                    ['nama_lengkap' => $mhs['nama_lengkap'], 'prodi_id' => $mhs['prodi_id']]
                );
                $kompetisi->mahasiswa()->attach($mahasiswa->id, ['juara' => $mhs['capaian']]);
            }
        }

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
        
        $kompetisi = Kompetisi::with('mahasiswa')->findOrFail($id);
        $jenisOptions = Kompetisi::getJenisOptions();
        $prodis = Prodi::all();
        return view('master-data.kompetisi.edit', compact('kompetisi', 'jenisOptions', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('master-data-kompetisi.edit');
        
        $request->validate([
            'nama_kompetisi' => 'required|string|max:255',
            'jenis' => 'required',
            'nama_penyelenggara' => 'required',
            'tingkat_kompetisi' => 'required',
            'tanggal_kompetisi' => 'required|date',
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*.nama_lengkap' => 'required|string',
            'mahasiswa.*.nim' => 'required|numeric',
            'mahasiswa.*.prodi_id' => 'required|exists:prodi,id',
            'mahasiswa.*.capaian' => 'required|string',
        ]);

        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->update($request->except('mahasiswa'));

        if ($request->has('mahasiswa') && is_array($request->mahasiswa)) {
            $syncData = [];
            foreach ($request->mahasiswa as $mhs) {
                $mahasiswa = Mahasiswa::updateOrCreate(
                    ['nim' => $mhs['nim']],
                    ['nama_lengkap' => $mhs['nama_lengkap'], 'prodi_id' => $mhs['prodi_id']]
                );
                $syncData[$mahasiswa->id] = ['juara' => $mhs['capaian']];
            }
            $kompetisi->mahasiswa()->sync($syncData);
        } else {
            $kompetisi->mahasiswa()->detach();
        }

        return redirect()->route('kompetisi.index')->with('success', 'Data Kompetisi berhasil diperbarui!');
    }
}