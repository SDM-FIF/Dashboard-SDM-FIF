<?php

namespace App\Http\Controllers;

use App\Models\TenagaPendukungAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenagaPendukungAkademikController extends Controller
{
    /**
     * Display a listing of TPA.
     */
    public function index(Request $request)
    {
        $query = TenagaPendukungAkademik::with('user');

        // Filter lokasi kerja
        if ($request->filled('lokasi_kerja')) {
            $query->where('lokasi_kerja', $request->lokasi_kerja);
        }

        // Filter status pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search nama
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
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
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
        }

        $tpa = $query->paginate(15);

        $filterData = [
            'lokasi_kerja' => TenagaPendukungAkademik::distinct()->pluck('lokasi_kerja')->filter(),
            'status_pegawai' => TenagaPendukungAkademik::distinct()->pluck('status_pegawai')->filter(),
        ];

        return view('manajemen-tpa.kelola-data', compact('tpa', 'filterData'));
    }
    
    public function kelolaData(Request $request)
    {
        $query = TenagaPendukungAkademik::with('user');

        // Filter lokasi kerja
        if ($request->filled('lokasi_kerja')) {
            $query->where('lokasi_kerja', $request->lokasi_kerja);
        }

        // Filter status pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search nama
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'nama-az':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'nama-za':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
        }

        $tpa = $query->paginate(15);

        $filterData = [
            'lokasi_kerja' => TenagaPendukungAkademik::distinct()->pluck('lokasi_kerja')->filter(),
            'status_pegawai' => TenagaPendukungAkademik::distinct()->pluck('status_pegawai')->filter(),
        ];

        return view('manajemen-tpa.kelola-data', compact('tpa', 'filterData'));
    }

    /**
     * Form Import Data
     */
    public function importForm()
    {
        return view('manajemen-tpa.import-data');
    }

    /**
     * Process Import Data
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        // TODO: implementasi import Excel/CSV

        return redirect()->route('manajemen-tpa.kelola-data')
            ->with('success', 'Data berhasil diimport!');
    }

    /**
     * Halaman Laporan TPA
     */
    public function laporan()
    {
        $statistik = [
            'total' => TenagaPendukungAkademik::count(),
            'per_status' => TenagaPendukungAkademik::selectRaw('status_pegawai, COUNT(*) as total')
                ->groupBy('status_pegawai')
                ->pluck('total', 'status_pegawai'),
            'per_lokasi' => TenagaPendukungAkademik::selectRaw('lokasi_kerja, COUNT(*) as total')
                ->groupBy('lokasi_kerja')
                ->pluck('total', 'lokasi_kerja'),
        ];

        return view('manajemen-tpa.laporan', compact('statistik'));
    }

    /**
     * Show the form for creating a new TPA.
     */
    public function create()
    {
        return view('manajemen-tpa.tambah-data');
    }

    /**
     * Store a newly created TPA in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:tenaga_pendukung_akademik,nip',
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_pegawai' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'username' => 'required|string|max:100|unique:user,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create user
            $user = User::create([
                'role_id' => 1, // ⬅️ sesuaikan role TPA jika ada role khusus
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create TPA
            TenagaPendukungAkademik::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'pangkat_golongan' => $validated['pangkat_golongan'],
                'status_pegawai' => $validated['status_pegawai'],
                'lokasi_kerja' => $validated['lokasi_kerja'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            ]);

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified TPA.
     */
public function show(Request $request, TenagaPendukungAkademik $tpa)
{
    // load relasi user
    $tpa->load('user');

    // Ambil semua TPA untuk list di bawah detail (dengan filter & sort)
    $query = TenagaPendukungAkademik::with('user');

    // Filter status pegawai jika ada
    if ($request->filled('filter_status')) {
        $query->where('status_pegawai', $request->filter_status);
    }

    // Sort
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'nama-az':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'nama-za':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
                break;
        }
    } else {
        $query->orderBy('id', 'desc');
    }

    $allTpa = $query->paginate(10)->appends($request->query());

    return view('manajemen-tpa.detail-data', compact('tpa', 'allTpa'));
}


    /**
     * Show the form for editing the specified TPA.
     */
    public function edit(TenagaPendukungAkademik $tpa)
    {
        $tpa->load('user');
        return view('manajemen-tpa.edit-data', compact('tpa'));
    }

    /**
     * Update the specified TPA in storage.
     */
    public function update(Request $request, TenagaPendukungAkademik $tpa)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:tenaga_pendukung_akademik,nip,' . $tpa->id,
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_pegawai' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'username' => 'required|string|max:100|unique:user,username,' . $tpa->user_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Update user
            $userData = [
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $tpa->user->update($userData);

            // Update TPA
            $tpa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'pangkat_golongan' => $validated['pangkat_golongan'],
                'status_pegawai' => $validated['status_pegawai'],
                'lokasi_kerja' => $validated['lokasi_kerja'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            ]);

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified TPA from storage.
     */
    public function destroy(TenagaPendukungAkademik $tpa)
    {
        try {
            $user = $tpa->user;
            $tpa->delete();
            $user->delete();

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman laporan TPA
     */

}
