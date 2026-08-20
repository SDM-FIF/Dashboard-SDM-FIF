<?php

namespace App\Http\Controllers;

use App\Models\TahunAjar;
use App\Models\JadwalPengujian;
use App\Models\CalonDosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TahunAjarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('master-data-tahun-ajar.view');

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
        $this->authorize('master-data-tahun-ajar.create');

        return view('master-data.tahun-ajar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('master-data-tahun-ajar.create');

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

        $ta = TahunAjar::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => $request->has('is_active'),
        ]);

        \App\Models\Notification::sendToAll('Data Baru', "Tahun Ajaran baru telah ditambahkan: {$ta->label}", route('tahun-ajar.index'));

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Tahun Ajaran baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('master-data-tahun-ajar.edit');

        $tahunAjar = TahunAjar::findOrFail($id);

        return view('master-data.tahun-ajar.edit', compact('tahunAjar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-data-tahun-ajar.edit');

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
            'is_active' => $request->has('is_active'),
        ]);

        \App\Models\Notification::sendToAll('Perubahan Data', "Data Tahun Ajaran {$tahunAjar->label} telah diperbarui", route('tahun-ajar.index'));

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Data Tahun Ajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('master-data-tahun-ajar.delete');

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

        $labelTa = $tahunAjar->label;
        $tahunAjar->delete();

        \App\Models\Notification::sendToAll('Perubahan Data', "Tahun Ajaran {$labelTa} telah dihapus");

        return redirect()
            ->route('tahun-ajar.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('master-data-tahun-ajar.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-tahun-ajaran-' . date('Y-m-d') . '.' . $format;

        $query = TahunAjar::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tahun', 'like', '%' . $search . '%')
                  ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }

        $data = $query->orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get()->map(function ($t) {
            return [
                'Tahun Academic' => $t->tahun . '/' . ($t->tahun + 1),
                'Semester' => $t->semester == 1 ? 'Ganjil (1)' : 'Genap (2)',
                'Tahun' => $t->tahun,
                'Status' => $t->is_active ? 'Aktif' : 'Tidak Aktif',
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Tahun Academic', 'Semester', 'Tahun', 'Status'];
                }
            },
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('master-data-tahun-ajar.view');

        $query = TahunAjar::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tahun', 'like', '%' . $search . '%')
                  ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }

        $tahunAjar = $query->orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA TAHUN AJARAN</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="10%">No</th>
                    <th width="30%">Tahun Akademik</th>
                    <th width="25%">Semester</th>
                    <th width="15%">Tahun</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($tahunAjar as $index => $t) {
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . $t->tahun . '/' . ($t->tahun + 1) . '</td>
                    <td>' . ($t->semester == 1 ? 'Ganjil (1)' : 'Genap (2)') . '</td>
                    <td style="text-align: center;">' . $t->tahun . '</td>
                    <td style="text-align: center;">' . ($t->is_active ? 'Aktif' : 'Tidak Aktif') . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('data-tahun-ajaran-' . date('Y-m-d-His') . '.pdf');
    }
}
