<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('master-data-fakultas.view');
        
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
        $this->authorize('master-data-fakultas.edit');
        
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();
        return view('master-data.fakultas.create', compact('dosenList'));
    }

    public function edit($id)
    {
        $this->authorize('master-data-fakultas.edit');
        
        $fakultas = Fakultas::findOrFail($id);
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();

        return view('master-data.fakultas.edit', compact('fakultas', 'dosenList'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('master-data-fakultas.edit');
        
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

    public function exportExcel(Request $request)
    {
        $this->authorize('master-data-fakultas.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-fakultas-' . date('Y-m-d') . '.' . $format;

        $query = Fakultas::with(['dekan', 'wadek1', 'wadek2']);
        if ($request->filled('search')) {
            $query->where('nama_fakultas', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('nama_fakultas', 'asc')->get()->map(function ($f) {
            return [
                'Nama Fakultas' => $f->nama_fakultas,
                'Dekan' => $f->dekan->nama_lengkap ?? '-',
                'Wakil Dekan I' => $f->wadek1->nama_lengkap ?? '-',
                'Wakil Dekan II' => $f->wadek2->nama_lengkap ?? '-',
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Nama Fakultas', 'Dekan', 'Wakil Dekan I', 'Wakil Dekan II'];
                }
            },
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('master-data-fakultas.view');

        $query = Fakultas::with(['dekan', 'wadek1', 'wadek2']);
        if ($request->filled('search')) {
            $query->where('nama_fakultas', 'like', '%' . $request->search . '%');
        }

        $fakultas = $query->orderBy('nama_fakultas', 'asc')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA FAKULTAS</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="5%">No</th>
                    <th width="35%">Nama Fakultas</th>
                    <th width="20%">Dekan</th>
                    <th width="20%">Wakil Dekan I</th>
                    <th width="20%">Wakil Dekan II</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($fakultas as $index => $f) {
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . $f->nama_fakultas . '</td>
                    <td>' . ($f->dekan->nama_lengkap ?? '-') . '</td>
                    <td>' . ($f->wadek1->nama_lengkap ?? '-') . '</td>
                    <td>' . ($f->wadek2->nama_lengkap ?? '-') . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('data-fakultas-' . date('Y-m-d-His') . '.pdf');
    }
}