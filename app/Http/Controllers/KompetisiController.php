<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        \App\Models\Notification::sendToAll('Data Baru', "Kompetisi baru telah ditambahkan: {$kompetisi->nama_kompetisi}", route('kompetisi.show', $kompetisi->id));

        return redirect()->route('kompetisi.index')->with('success', 'Data Kompetisi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $this->authorize('master-data-kompetisi.delete');
        
        $kompetisi = Kompetisi::findOrFail($id);
        $namaKomp = $kompetisi->nama_kompetisi;
        $kompetisi->delete();

        \App\Models\Notification::sendToAll('Perubahan Data', "Kompetisi {$namaKomp} telah dihapus");

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

        \App\Models\Notification::sendToAll('Perubahan Data', "Data kompetisi {$kompetisi->nama_kompetisi} telah diperbarui", route('kompetisi.show', $kompetisi->id));

        return redirect()->route('kompetisi.index')->with('success', 'Data Kompetisi berhasil diperbarui!');
    }

    public function show($id)
    {
        $this->authorize('master-data-kompetisi.view');
        
        $kompetisi = Kompetisi::with('mahasiswa.prodi')->findOrFail($id);
        return view('master-data.kompetisi.show', compact('kompetisi'));
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('master-data-kompetisi.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-kompetisi-' . date('Y-m-d') . '.' . $format;

        $query = Kompetisi::query();
        if ($request->filled('search')) {
            $query->where('nama_kompetisi', 'like', '%' . $request->search . '%');
        }

        $data = $query->latest('id')->get()->map(function ($k) {
            return [
                'Nama Kompetisi' => $k->nama_kompetisi,
                'Jenis' => $k->jenis ?? '-',
                'Penyelenggara' => $k->nama_penyelenggara ?? '-',
                'Tingkat' => $k->tingkat_kompetisi ?? '-',
                'Tanggal' => $k->tanggal_kompetisi ? \Carbon\Carbon::parse($k->tanggal_kompetisi)->format('d-m-Y') : '-',
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Nama Kompetisi', 'Jenis', 'Penyelenggara', 'Tingkat', 'Tanggal'];
                }
            },
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('master-data-kompetisi.view');

        $query = Kompetisi::query();
        if ($request->filled('search')) {
            $query->where('nama_kompetisi', 'like', '%' . $request->search . '%');
        }

        $kompetisi = $query->latest('id')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA MASTER KOMPETISI</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="5%">No</th>
                    <th width="35%">Nama Kompetisi</th>
                    <th width="15%">Jenis</th>
                    <th width="25%">Penyelenggara</th>
                    <th width="20%">Tingkat</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($kompetisi as $index => $k) {
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . $k->nama_kompetisi . '</td>
                    <td>' . ($k->jenis ?? '-') . '</td>
                    <td>' . ($k->nama_penyelenggara ?? '-') . '</td>
                    <td>' . ($k->tingkat_kompetisi ?? '-') . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('data-kompetisi-' . date('Y-m-d-His') . '.pdf');
    }
}