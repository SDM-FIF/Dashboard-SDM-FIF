<?php

namespace App\Http\Controllers;

use App\Models\SuratDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratDosenController extends Controller
{
    /**
     * Display a listing of ST and SK.
     */
    public function index(Request $request)
    {
        $this->authorize('kelola-data-dosen.view');

        $query = SuratDosen::with('dosen');

        // Filter Jenis Surat (Surat Tugas / Surat Keputusan)
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Dosen
        if ($request->filled('dosen_id')) {
            $query->where('dosen_id', $request->dosen_id);
        }

        // Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', '%' . $search . '%')
                  ->orWhere('judul_surat', 'like', '%' . $search . '%')
                  ->orWhereHas('dosen', function ($qd) use ($search) {
                      $qd->where('nama_lengkap', 'like', '%' . $search . '%')
                         ->orWhere('nip', 'like', '%' . $search . '%')
                         ->orWhere('kode_dosen', 'like', '%' . $search . '%');
                  });
            });
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('tanggal_surat', 'asc');
                break;
            case 'nomor-az':
                $query->orderBy('nomor_surat', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('tanggal_surat', 'desc')->orderBy('id', 'desc');
                break;
        }

        $suratList = $query->paginate(10)->appends($request->query());
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.index', compact('suratList', 'dosenList', 'kategoriList'));
    }

    /**
     * Show form to create new ST / SK.
     */
    public function create(Request $request)
    {
        $this->authorize('kelola-data-dosen.create');

        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();
        $selectedDosenId = $request->query('dosen_id');

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.create', compact('dosenList', 'selectedDosenId', 'kategoriList'));
    }

    /**
     * Store new ST / SK.
     */
    public function store(Request $request)
    {
        $this->authorize('kelola-data-dosen.create');

        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'jenis_surat' => 'required|in:Surat Tugas,Surat Keputusan',
            'nomor_surat' => 'required|string|max:100',
            'judul_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_selesai' => 'nullable|date|after_or_equal:berlaku_mulai',
            'kategori' => 'required|string|max:100',
            'file_surat' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'keterangan' => 'nullable|string',
        ], [
            'dosen_id.required' => 'Dosen wajib dipilih!',
            'jenis_surat.required' => 'Jenis Surat wajib dipilih!',
            'nomor_surat.required' => 'Nomor Surat wajib diisi!',
            'judul_surat.required' => 'Judul / Perihal Surat wajib diisi!',
            'tanggal_surat.required' => 'Tanggal Surat wajib diisi!',
            'file_surat.required' => 'Dokumen berkas Surat wajib diunggah!',
            'file_surat.mimes' => 'Format berkas hanya diperbolehkan PDF, DOC, atau DOCX!',
            'file_surat.max' => 'Ukuran berkas maksimal 10 MB!',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat-dosen', 'public');
        }

        $kategoriVal = $request->kategori;
        if ($request->kategori === 'Lainnya' && $request->filled('kategori_lainnya')) {
            $kategoriVal = trim($request->kategori_lainnya);
        }

        SuratDosen::create([
            'dosen_id' => $request->dosen_id,
            'jenis_surat' => $request->jenis_surat,
            'nomor_surat' => trim($request->nomor_surat),
            'judul_surat' => trim($request->judul_surat),
            'tanggal_surat' => $request->tanggal_surat,
            'berlaku_mulai' => $request->berlaku_mulai,
            'berlaku_selesai' => $request->berlaku_selesai,
            'kategori' => $kategoriVal,
            'file_surat' => $filePath,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('manajemen-dosen.surat.index')
            ->with('success', "{$request->jenis_surat} baru berhasil disimpan!");
    }

    /**
     * Display details & PDF preview of ST / SK.
     */
    public function show($id)
    {
        $this->authorize('kelola-data-dosen.view');

        $surat = SuratDosen::with('dosen.prodi')->findOrFail($id);

        return view('manajemen-dosen.surat.show', compact('surat'));
    }

    /**
     * Show form to edit ST / SK.
     */
    public function edit($id)
    {
        $this->authorize('kelola-data-dosen.edit');

        $surat = SuratDosen::findOrFail($id);
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.edit', compact('surat', 'dosenList', 'kategoriList'));
    }

    /**
     * Update ST / SK.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('kelola-data-dosen.edit');

        $surat = SuratDosen::findOrFail($id);

        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'jenis_surat' => 'required|in:Surat Tugas,Surat Keputusan',
            'nomor_surat' => 'required|string|max:100',
            'judul_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_selesai' => 'nullable|date|after_or_equal:berlaku_mulai',
            'kategori' => 'required|string|max:100',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'keterangan' => 'nullable|string',
        ], [
            'dosen_id.required' => 'Dosen wajib dipilih!',
            'jenis_surat.required' => 'Jenis Surat wajib dipilih!',
            'nomor_surat.required' => 'Nomor Surat wajib diisi!',
            'judul_surat.required' => 'Judul / Perihal Surat wajib diisi!',
            'tanggal_surat.required' => 'Tanggal Surat wajib diisi!',
            'file_surat.mimes' => 'Format berkas hanya diperbolehkan PDF, DOC, atau DOCX!',
            'file_surat.max' => 'Ukuran berkas maksimal 10 MB!',
        ]);

        $filePath = $surat->file_surat;
        if ($request->hasFile('file_surat')) {
            // Delete old file
            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }
            $filePath = $request->file('file_surat')->store('surat-dosen', 'public');
        }

        $kategoriVal = $request->kategori;
        if ($request->kategori === 'Lainnya' && $request->filled('kategori_lainnya')) {
            $kategoriVal = trim($request->kategori_lainnya);
        }

        $surat->update([
            'dosen_id' => $request->dosen_id,
            'jenis_surat' => $request->jenis_surat,
            'nomor_surat' => trim($request->nomor_surat),
            'judul_surat' => trim($request->judul_surat),
            'tanggal_surat' => $request->tanggal_surat,
            'berlaku_mulai' => $request->berlaku_mulai,
            'berlaku_selesai' => $request->berlaku_selesai,
            'kategori' => $kategoriVal,
            'file_surat' => $filePath,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('manajemen-dosen.surat.index')
            ->with('success', "Data {$request->jenis_surat} berhasil diperbarui!");
    }

    /**
     * Delete ST / SK and remove file from disk.
     */
    public function destroy($id)
    {
        $this->authorize('kelola-data-dosen.delete');

        $surat = SuratDosen::findOrFail($id);

        if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
            Storage::disk('public')->delete($surat->file_surat);
        }

        $jenisSurat = $surat->jenis_surat;
        $surat->delete();

        return redirect()
            ->route('manajemen-dosen.surat.index')
            ->with('success', "{$jenisSurat} berhasil dihapus!");
    }

    /**
     * Download ST / SK file.
     */
    public function download($id)
    {
        $this->authorize('kelola-data-dosen.view');

        $surat = SuratDosen::findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'Dokumen berkas tidak ditemukan di server!');
        }

        $extension = pathinfo($surat->file_surat, PATHINFO_EXTENSION);
        $cleanFileName = str_replace('/', '_', $surat->nomor_surat) . '.' . $extension;

        return Storage::disk('public')->download($surat->file_surat, $cleanFileName);
    }
}
