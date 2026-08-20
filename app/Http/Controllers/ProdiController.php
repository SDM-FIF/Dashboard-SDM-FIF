<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $dosen = Dosen::orderBy('nama_lengkap')->get();
        return view('master-data.prodi.create', compact('fakultas', 'dosen'));
    }

    public function store(Request $request)
    {
        $this->authorize('master-data-prodi.create');

        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'kaprodi' => 'nullable|string|max:255',
            'batas_nisbah' => 'required|integer|min:1',
        ]);

    $p = Prodi::create($validated);

    \App\Models\Notification::sendToAll('Data Baru', "Program Studi baru telah ditambahkan: {$p->nama_prodi}", route('prodi.index'));

    return redirect()
        ->route('prodi.index')
        ->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $this->authorize('master-data-prodi.edit');

        $prodi = Prodi::findOrFail($id);
        $fakultas = Fakultas::all();
        $dosen = Dosen::orderBy('nama_lengkap')->get();

        return view('master-data.prodi.edit', compact('prodi', 'fakultas', 'dosen'));
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

        $namaProdi = $prodi->nama_prodi;
        $prodi->delete();

        \App\Models\Notification::sendToAll('Perubahan Data', "Program Studi {$namaProdi} telah dihapus");

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
         $this->authorize('master-data-prodi.edit');

        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'kaprodi' => 'nullable|string|max:255',
            'batas_nisbah' => 'required|integer|min:1',
        ]);

    $prodi = Prodi::findOrFail($id);
    $prodi->update($validated);

    \App\Models\Notification::sendToAll('Perubahan Data', "Data Program Studi {$prodi->nama_prodi} telah diperbarui", route('prodi.index'));

    return redirect()
        ->route('prodi.index')
        ->with('success', 'Data Prodi berhasil diperbarui!');
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('master-data-prodi.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-program-studi-' . date('Y-m-d') . '.' . $format;

        $query = Prodi::with('fakultas');
        if ($request->filled('search')) {
            $query->where('nama_prodi', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('nama_prodi', 'asc')->get()->map(function ($p) {
            return [
                'Kode Prodi' => $p->kode_prodi ?? '-',
                'Nama Program Studi' => $p->nama_prodi,
                'Jenjang' => $p->jenjang ?? '-',
                'Fakultas' => $p->fakultas->nama_fakultas ?? '-',
                'Kaprodi' => $p->kaprodi ?? '-',
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Kode Prodi', 'Nama Program Studi', 'Jenjang', 'Fakultas', 'Kaprodi'];
                }
            },
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('master-data-prodi.view');

        $query = Prodi::with('fakultas');
        if ($request->filled('search')) {
            $query->where('nama_prodi', 'like', '%' . $request->search . '%');
        }

        $prodi = $query->orderBy('nama_prodi', 'asc')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA PROGRAM STUDI</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="5%">No</th>
                    <th width="15%">Kode Prodi</th>
                    <th width="30%">Nama Program Studi</th>
                    <th width="10%">Jenjang</th>
                    <th width="20%">Fakultas</th>
                    <th width="20%">Kaprodi</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($prodi as $index => $p) {
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . ($p->kode_prodi ?? '-') . '</td>
                    <td>' . $p->nama_prodi . '</td>
                    <td>' . ($p->jenjang ?? '-') . '</td>
                    <td>' . ($p->fakultas->nama_fakultas ?? '-') . '</td>
                    <td>' . ($p->kaprodi ?? '-') . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('data-program-studi-' . date('Y-m-d-His') . '.pdf');
    }
}
