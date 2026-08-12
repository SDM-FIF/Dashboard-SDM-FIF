<?php

namespace App\Http\Controllers;

use App\Models\KelompokKeahlian;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KelompokKeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('master-data-kelompok-keahlian.view');

        $query = KelompokKeahlian::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('singkatan', 'like', '%' . $search . '%')
                  ->orWhere('nama_kelompok_keahlian', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'nama-az':
                $query->orderBy('nama_kelompok_keahlian', 'asc');
                break;
            case 'nama-za':
                $query->orderBy('nama_kelompok_keahlian', 'desc');
                break;
            case 'singkatan-az':
                $query->orderBy('singkatan', 'asc');
                break;
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $kelompokKeahlian = $query->paginate(10)->appends($request->query());

        return view('master-data.kelompok-keahlian.index', compact('kelompokKeahlian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('master-data-kelompok-keahlian.create');

        return view('master-data.kelompok-keahlian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('master-data-kelompok-keahlian.create');

        $request->validate([
            'singkatan' => 'required|string|max:20|unique:kelompok_keahlian,singkatan',
            'nama_kelompok_keahlian' => 'required|string|max:255|unique:kelompok_keahlian,nama_kelompok_keahlian',
        ], [
            'singkatan.required' => 'Singkatan Kelompok Keahlian wajib diisi!',
            'singkatan.unique' => 'Singkatan Kelompok Keahlian tersebut sudah ada!',
            'nama_kelompok_keahlian.required' => 'Nama Kelompok Keahlian wajib diisi!',
            'nama_kelompok_keahlian.unique' => 'Nama Kelompok Keahlian tersebut sudah terdaftar!',
        ]);

        KelompokKeahlian::create([
            'singkatan' => trim($request->singkatan),
            'nama_kelompok_keahlian' => trim($request->nama_kelompok_keahlian),
        ]);

        return redirect()
            ->route('kelompok-keahlian.index')
            ->with('success', 'Kelompok Keahlian baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('master-data-kelompok-keahlian.edit');

        $kelompokKeahlian = KelompokKeahlian::findOrFail($id);

        return view('master-data.kelompok-keahlian.edit', compact('kelompokKeahlian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-data-kelompok-keahlian.edit');

        $request->validate([
            'singkatan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kelompok_keahlian', 'singkatan')->ignore($id),
            ],
            'nama_kelompok_keahlian' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelompok_keahlian', 'nama_kelompok_keahlian')->ignore($id),
            ],
        ], [
            'singkatan.required' => 'Singkatan Kelompok Keahlian wajib diisi!',
            'singkatan.unique' => 'Singkatan Kelompok Keahlian tersebut sudah digunakan!',
            'nama_kelompok_keahlian.required' => 'Nama Kelompok Keahlian wajib diisi!',
            'nama_kelompok_keahlian.unique' => 'Nama Kelompok Keahlian tersebut sudah terdaftar!',
        ]);

        $kelompokKeahlian = KelompokKeahlian::findOrFail($id);
        $kelompokKeahlian->update([
            'singkatan' => trim($request->singkatan),
            'nama_kelompok_keahlian' => trim($request->nama_kelompok_keahlian),
        ]);

        return redirect()
            ->route('kelompok-keahlian.index')
            ->with('success', 'Data Kelompok Keahlian berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('master-data-kelompok-keahlian.delete');

        $kelompokKeahlian = KelompokKeahlian::findOrFail($id);

        // Check if referenced by Dosen
        $jumlahDosen = Dosen::where('kelompok_keahlian_id', $id)->count();

        if ($jumlahDosen > 0) {
            return redirect()
                ->route('kelompok-keahlian.index')
                ->with('error', "Gagal menghapus! Kelompok Keahlian ini masih digunakan oleh {$jumlahDosen} dosen.");
        }

        $kelompokKeahlian->delete();

        return redirect()
            ->route('kelompok-keahlian.index')
            ->with('success', 'Data Kelompok Keahlian berhasil dihapus!');
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('master-data-kelompok-keahlian.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-kelompok-keahlian-' . date('Y-m-d') . '.' . $format;

        $query = KelompokKeahlian::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('singkatan', 'like', '%' . $search . '%')
                  ->orWhere('nama_kelompok_keahlian', 'like', '%' . $search . '%');
            });
        }

        $data = $query->orderBy('singkatan', 'asc')->get()->map(function ($k) {
            return [
                'Singkatan / Kode KK' => $k->singkatan,
                'Nama Kelompok Keahlian' => $k->nama_kelompok_keahlian,
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Singkatan / Kode KK', 'Nama Kelompok Keahlian'];
                }
            },
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('master-data-kelompok-keahlian.view');

        $query = KelompokKeahlian::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('singkatan', 'like', '%' . $search . '%')
                  ->orWhere('nama_kelompok_keahlian', 'like', '%' . $search . '%');
            });
        }

        $kkList = $query->orderBy('singkatan', 'asc')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA KELOMPOK KEAHLIAN (KK)</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="10%">No</th>
                    <th width="25%">Singkatan / Kode KK</th>
                    <th width="65%">Nama Kelompok Keahlian</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($kkList as $index => $k) {
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . $k->singkatan . '</td>
                    <td>' . $k->nama_kelompok_keahlian . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('data-kelompok-keahlian-' . date('Y-m-d-His') . '.pdf');
    }
}
