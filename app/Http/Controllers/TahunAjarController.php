<?php

namespace App\Http\Controllers;

use App\Models\TahunAjar;
use App\Models\JadwalPengujian;
use App\Models\CalonDosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TahunAjarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('master-data-prodi.view');

        $query = TahunAjar::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tahun', 'like', '%' . $search . '%')
                  ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('tahun', 'asc')->orderBy('semester', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('tahun', 'desc')->orderBy('semester', 'desc');
                break;
        }

        $tahunAjar = $query->paginate(10)->appends($request->query());

        return view('master-data.tahun-ajar.index', compact('tahunAjar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('master-data-prodi.create');

        return view('master-data.tahun-ajar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('master-data-prodi.create');

        $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:1900',
                'max:2100',
                Rule::unique('tahun_ajar')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                })
            ],
            'semester' => 'required|in:1,2',
        ], [
            'tahun.unique' => 'Tahun Ajaran dan Semester tersebut sudah terdaftar!',
        ]);

        TahunAjar::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
        ]);

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Tahun Ajaran baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('master-data-prodi.edit');

        $tahunAjar = TahunAjar::findOrFail($id);

        return view('master-data.tahun-ajar.edit', compact('tahunAjar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-data-prodi.edit');

        $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:1900',
                'max:2100',
                Rule::unique('tahun_ajar')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                })->ignore($id)
            ],
            'semester' => 'required|in:1,2',
        ], [
            'tahun.unique' => 'Tahun Ajaran dan Semester tersebut sudah terdaftar!',
        ]);

        $tahunAjar = TahunAjar::findOrFail($id);
        $tahunAjar->update([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
        ]);

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Data Tahun Ajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('master-data-prodi.delete');

        $tahunAjar = TahunAjar::findOrFail($id);

        // Check if referenced by Jadwal Pengujian or Calon Dosen
        $jumlahJadwal = JadwalPengujian::where('tahun_ajar_id', $id)->count();
        $jumlahCalonDosen = CalonDosen::where('tahun_ajar_id', $id)->count();

        if ($jumlahJadwal > 0 || $jumlahCalonDosen > 0) {
            $pesan = [];

            if ($jumlahJadwal > 0) {
                $pesan[] = "{$jumlahJadwal} jadwal pengujian";
            }

            if ($jumlahCalonDosen > 0) {
                $pesan[] = "{$jumlahCalonDosen} calon dosen";
            }

            return redirect()
                ->route('tahun-ajar.index')
                ->with(
                    'error',
                    'Tidak dapat menghapus Tahun Ajaran karena masih digunakan oleh ' .
                    implode(' dan ', $pesan) . '.'
                );
        }

        $tahunAjar->delete();

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
