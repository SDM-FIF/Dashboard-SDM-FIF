<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of Mahasiswa.
     */
    public function index(Request $request)
    {
        // Eager load relasi prodi untuk efisiensi query
        $query = Mahasiswa::with('prodi');

        // Filter Program Studi (pengganti lokasi_kerja)
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter Status (AKTIF, CUTI, dll)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search nama atau NIM
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'nama-az':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'nama-za':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'nim-asc':
                $query->orderBy('nim', 'asc');
                break;
            case 'nim-desc':
                $query->orderBy('nim', 'desc');
                break;
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
        }

        $mahasiswa = $query->paginate(15);

        // Data untuk dropdown filter
        $filterData = [
            'prodi' => Prodi::all(), // Ambil data dari tabel prodi
            'status' => ['AKTIF', 'TIDAK AKTIF', 'CUTI'], // Sesuai ENUM di database
        ];

        return view('mahasiswa.kelola-data', compact('mahasiswa', 'filterData'));
    }
    
    // Method alias untuk route yang mungkin berbeda tapi fungsi sama
    public function kelolaData(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Form Import Data
     */
    public function importForm()
    {
        return view('mahasiswa.import-data');
    }

    /**
     * Process Import Data
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        // TODO: Implementasi import Excel (misal menggunakan Maatwebsite/Excel)
        // Pastikan mapping kolom: prodi_id, nama_lengkap, nim, status

        return redirect()->route('mahasiswa.kelola-data')
            ->with('success', 'Data Mahasiswa berhasil diimport!');
    }

    /**
     * Halaman Laporan Mahasiswa
     */
    public function laporan()
    {
        $statistik = [
            'total' => Mahasiswa::count(),
            // Statistik per Status
            'per_status' => Mahasiswa::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            // Statistik per Prodi (Join ke tabel prodi untuk ambil nama)
            'per_prodi' => Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->selectRaw('prodi.nama_prodi, COUNT(mahasiswa.id) as total')
                ->groupBy('prodi.nama_prodi')
                ->pluck('total', 'nama_prodi'),
        ];

        return view('mahasiswa.laporan', compact('statistik'));
    }

    /**
     * Show the form for creating a new Mahasiswa.
     */
    public function create()
    {
        $prodi = Prodi::all();
        return view('mahasiswa.tambah-data', compact('prodi'));
    }

    /**
     * Store a newly created Mahasiswa in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim',
            'prodi_id' => 'required|exists:prodi,id',
            'status' => 'required|in:AKTIF,TIDAK AKTIF,CUTI',
        ]);

        try {
            // Create Mahasiswa
            // Note: Tidak membuat User karena tabel mahasiswa di SQL tidak ada user_id
            Mahasiswa::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nim' => $validated['nim'],
                'prodi_id' => $validated['prodi_id'],
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('mahasiswa.kelola-data')
                ->with('success', 'Data Mahasiswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified Mahasiswa.
     */
    public function show(Request $request, Mahasiswa $mahasiswa)
    {
        // Load relasi prodi dan kompetisi (jika ada)
        $mahasiswa->load(['prodi', 'kompetisi']);

        // List pagination di bawah detail (opsional, meniru controller TPA)
        $query = Mahasiswa::with('prodi');

        if ($request->filled('filter_prodi')) {
            $query->where('prodi_id', $request->filter_prodi);
        }

        $allMahasiswa = $query->paginate(10)->appends($request->query());

        return view('mahasiswa.detail-data', compact('mahasiswa', 'allMahasiswa'));
    }

    /**
     * Show the form for editing the specified Mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $prodi = Prodi::all();
        return view('mahasiswa.edit-data', compact('mahasiswa', 'prodi'));
    }

    /**
     * Update the specified Mahasiswa in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim,' . $mahasiswa->id,
            'prodi_id' => 'required|exists:prodi,id',
            'status' => 'required|in:AKTIF,TIDAK AKTIF,CUTI',
        ]);

        try {
            $mahasiswa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nim' => $validated['nim'],
                'prodi_id' => $validated['prodi_id'],
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('mahasiswa.kelola-data')
                ->with('success', 'Data Mahasiswa berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified Mahasiswa from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            // Hapus data mahasiswa
            // Karena relasi kompetisi menggunakan ON DELETE CASCADE di SQL, 
            // data kompetisi terkait otomatis terhapus.
            $mahasiswa->delete();

            return redirect()
                ->route('mahasiswa.kelola-data')
                ->with('success', 'Data Mahasiswa berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}