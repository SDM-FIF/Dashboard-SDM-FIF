<?php

namespace App\Http\Controllers;

use App\Models\RekrutasiDosen;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekrutasiDosenExport;
use Barryvdh\DomPDF\Facade\Pdf;

class RekrutasiDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = RekrutasiDosen::with('prodi');

        // Filter by Prodi
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }

        // Filter by Tahun Ajar
        if ($request->filled('tahun_ajar')) {
            $query->where('tahun_ajar', $request->tahun_ajar);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_calon', 'like', '%' . $request->search . '%');
        }

        // Sorting
        // Sorting
$sortColumn = $request->get('sort', 'created_at');
$sortOrder = $request->get('order', 'desc');

$allowedSorts = ['no_registrasi', 'nama_calon', 'tahun_ajar', 'status', 'created_at'];
if (in_array($sortColumn, $allowedSorts)) {
    $query->orderBy($sortColumn, $sortOrder);
} else {
    $query->latest('created_at');
}

        $rekrutasi = $query->paginate(10)->withQueryString();

        // Get filter data
        $filterData = [
            'prodi' => Prodi::all(),
            'tahun_ajar' => [
                'Ganjil 2024/2025',
                'Genap 2024/2025',
                'Ganjil 2025/2026',
                'Genap 2025/2026',
            ],
            'status' => RekrutasiDosen::getStatusOptions()
        ];

        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.rekrutasi-dosen', compact('rekrutasi', 'filterData'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        $tahunAjar = [
            'Ganjil 2024/2025',
            'Genap 2024/2025',
            'Ganjil 2025/2026',
            'Genap 2025/2026',
        ];
        
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.tambah-rekrutasi-dosen', compact('prodi', 'tahunAjar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_calon' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
            'tahun_ajar' => 'required|string',
            'tanggal_pengujian' => 'required|date',
            'jadwal' => 'nullable|string',
            'status' => 'required|in:Diajukan,Diproses,Diterima,Ditolak',
        ]);

        // Generate no registrasi otomatis
        $validated['no_registrasi'] = RekrutasiDosen::generateNoRegistrasi();

        RekrutasiDosen::create($validated);

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $rekrutasi = RekrutasiDosen::with(['prodi', 'jadwalPengujian.dosenPenguji'])->findOrFail($id);
        
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.detail-rekrutasi-dosen', compact('rekrutasi'));
    }

    public function edit($id)
    {
        $rekrutasi = RekrutasiDosen::findOrFail($id);
        $prodi = Prodi::all();
        $tahunAjar = [
            'Ganjil 2024/2025',
            'Genap 2024/2025',
            'Ganjil 2025/2026',
            'Genap 2025/2026',
        ];
        
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.edit-rekrutasi-dosen', compact('rekrutasi', 'prodi', 'tahunAjar'));
    }

    public function update(Request $request, $id)
    {
        $rekrutasi = RekrutasiDosen::findOrFail($id);

        $validated = $request->validate([
            'nama_calon' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
            'tahun_ajar' => 'required|string',
            'tanggal_pengujian' => 'required|date',
            'jadwal' => 'nullable|string',
            'status' => 'required|in:Diajukan,Diproses,Diterima,Ditolak',
        ]);

        $rekrutasi->update($validated);

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $rekrutasi = RekrutasiDosen::findOrFail($id);
        $rekrutasi->delete();

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil dihapus!');
    }

    public function importView()
    {
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.import-rekrutasi-dosen');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        // Import logic here (using Laravel Excel)
        
        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data berhasil diimport!');
    }

    public function jadwalPengujian()
    {
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.jadwal-pengujian-dosen');
    }

    public function hasilPengujian()
    {
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.hasil-pengujian-dosen');
    }

    public function exportExcel(Request $request)
{
    try {
        $fileName = 'rekrutasi-dosen-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new RekrutasiDosenExport($request->all()), 
            $fileName
        );
    } catch (\Exception $e) {
        \Log::error('Export Excel Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Export Excel gagal: ' . $e->getMessage());
    }
}

public function exportCsv(Request $request)
{
    try {
        $fileName = 'rekrutasi-dosen-' . date('Y-m-d-His') . '.csv';
        
        return Excel::download(
            new RekrutasiDosenExport($request->all()), 
            $fileName,
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    } catch (\Exception $e) {
        \Log::error('Export CSV Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Export CSV gagal: ' . $e->getMessage());
    }
}

public function exportPdf(Request $request)
{
    $query = RekrutasiDosen::with('prodi');
    
    // Apply filters
    if ($request->filled('prodi')) {
        $query->where('prodi_id', $request->prodi);
    }
    if ($request->filled('tahun_ajar')) {
        $query->where('tahun_ajar', $request->tahun_ajar);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    $rekrutasi = $query->latest()->get();
    
    $pdf = Pdf::loadView('rekrutasi-dosen.export-pdf', compact('rekrutasi'));
    $pdf->setPaper('a4', 'landscape');
    
    return $pdf->download('rekrutasi-dosen-' . date('Y-m-d-His') . '.pdf');
}
}