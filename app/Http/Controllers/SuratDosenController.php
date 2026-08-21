<?php

namespace App\Http\Controllers;

use App\Models\SuratDosen;
use App\Models\Dosen;
use App\Models\TenagaPendukungAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratDosenController extends Controller
{
    /**
     * Display a listing of ST and SK.
     */
    public function index(Request $request)
    {
        $this->authorize('kelola-data-dosen.view');

        $query = SuratDosen::with(['dosen', 'dosenList.prodi']);

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
            $dosenId = $request->dosen_id;
            $query->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->orWhereHas('dosenList', function ($qd) use ($dosenId) {
                      $qd->where('dosen.id', $dosenId);
                  });
            });
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
                  })
                  ->orWhereHas('dosenList', function ($qd) use ($search) {
                      $qd->where('nama_lengkap', 'like', '%' . $search . '%')
                         ->orWhere('nip', 'like', '%' . $search . '%')
                         ->orWhere('kode_dosen', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('tpaList', function ($qt) use ($search) {
                      $qt->where('nama_lengkap', 'like', '%' . $search . '%')
                         ->orWhere('nip', 'like', '%' . $search . '%');
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

        // Query for the new lecturers and their letters tab
        $dosenQuery = Dosen::has('suratDosen')->with(['suratDosen', 'prodi']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $dosenQuery->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('kode_dosen', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->filled('dosen_id')) {
            $dosenQuery->where('id', $request->dosen_id);
        }

        $dosenSuratList = $dosenQuery->orderBy('nama_lengkap', 'asc')
            ->paginate(10, ['*'], 'page_dosen')
            ->appends($request->query());

        // Query for TPA and their letters tab
        $tpaQuery = TenagaPendukungAkademik::has('suratDosen')->with(['suratDosen']);

        if ($request->filled('search')) {
            $search = $request->search;
            $tpaQuery->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $tpaSuratList = $tpaQuery->orderBy('nama_lengkap', 'asc')
            ->paginate(10, ['*'], 'page_tpa')
            ->appends($request->query());

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.index', compact('suratList', 'dosenList', 'kategoriList', 'dosenSuratList', 'tpaSuratList'));
    }

    /**
     * Show form to create new ST / SK.
     */
    public function create(Request $request)
    {
        $this->authorize('kelola-data-dosen.create');

        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();
        $tpaList = TenagaPendukungAkademik::orderBy('nama_lengkap', 'asc')->get();
        $selectedDosenId = $request->query('dosen_id');
        $selectedTpaId = $request->query('tpa_id');

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.create', compact('dosenList', 'tpaList', 'selectedDosenId', 'selectedTpaId', 'kategoriList'));
    }

    /**
     * Store new ST / SK.
     */
    public function store(Request $request)
    {
        $this->authorize('kelola-data-dosen.create');

        $request->validate([
            'dosen_ids' => 'required_without:tpa_ids|array',
            'dosen_ids.*' => 'exists:dosen,id',
            'tpa_ids' => 'required_without:dosen_ids|array',
            'tpa_ids.*' => 'exists:tenaga_pendukung_akademik,id',
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
            'dosen_ids.required_without' => 'Penerima Surat wajib dipilih (minimal 1 Dosen atau TPA)!',
            'tpa_ids.required_without' => 'Penerima Surat wajib dipilih (minimal 1 Dosen atau TPA)!',
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

        $primaryDosenId = $request->filled('dosen_ids') ? $request->dosen_ids[0] : null;

        $surat = SuratDosen::create([
            'dosen_id' => $primaryDosenId,
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

        // Sync multiple dosen recipients in pivot table with optional jabatan
        $syncData = [];
        $jabatans = $request->input('jabatan', []);
        if ($request->filled('dosen_ids')) {
            foreach ($request->dosen_ids as $dosenId) {
                $syncData[$dosenId] = [
                    'jabatan' => isset($jabatans[$dosenId]) ? trim($jabatans[$dosenId]) : null
                ];
            }
        }
        $surat->dosenList()->sync($syncData);

        // Sync multiple TPA recipients in pivot table with optional jabatan
        $syncDataTpa = [];
        $jabatansTpa = $request->input('jabatan_tpa', []);
        if ($request->filled('tpa_ids')) {
            foreach ($request->tpa_ids as $tpaId) {
                $syncDataTpa[$tpaId] = [
                    'jabatan' => isset($jabatansTpa[$tpaId]) ? trim($jabatansTpa[$tpaId]) : null
                ];
            }
        }
        $surat->tpaList()->sync($syncDataTpa);

        \App\Models\Notification::sendToAll('Informasi Baru', "Surat Tugas/SK baru: {$surat->jenis_surat} nomor {$surat->nomor_surat} telah diterbitkan", route('manajemen-dosen.surat.show', $surat->id));

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

        $surat = SuratDosen::with(['dosenList.prodi', 'tpaList'])->findOrFail($id);

        return view('manajemen-dosen.surat.show', compact('surat'));
    }

    /**
     * Show form to edit ST / SK.
     */
    public function edit($id)
    {
        $this->authorize('kelola-data-dosen.edit');

        $surat = SuratDosen::with(['dosenList', 'tpaList'])->findOrFail($id);
        $dosenList = Dosen::orderBy('nama_lengkap', 'asc')->get();
        $tpaList = TenagaPendukungAkademik::orderBy('nama_lengkap', 'asc')->get();

        $kategoriList = [
            'Pengajaran',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Jabatan Struktural',
            'Panitia / Kegiatan',
            'Lainnya',
        ];

        return view('manajemen-dosen.surat.edit', compact('surat', 'dosenList', 'tpaList', 'kategoriList'));
    }

    /**
     * Update ST / SK.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('kelola-data-dosen.edit');

        $surat = SuratDosen::findOrFail($id);

        $request->validate([
            'dosen_ids' => 'required_without:tpa_ids|array',
            'dosen_ids.*' => 'exists:dosen,id',
            'tpa_ids' => 'required_without:dosen_ids|array',
            'tpa_ids.*' => 'exists:tenaga_pendukung_akademik,id',
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
            'dosen_ids.required_without' => 'Penerima Surat wajib dipilih (minimal 1 Dosen atau TPA)!',
            'tpa_ids.required_without' => 'Penerima Surat wajib dipilih (minimal 1 Dosen atau TPA)!',
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

        $primaryDosenId = $request->filled('dosen_ids') ? $request->dosen_ids[0] : null;

        $surat->update([
            'dosen_id' => $primaryDosenId,
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

        // Sync multiple dosen recipients in pivot table with optional jabatan
        $syncData = [];
        $jabatans = $request->input('jabatan', []);
        if ($request->filled('dosen_ids')) {
            foreach ($request->dosen_ids as $dosenId) {
                $syncData[$dosenId] = [
                    'jabatan' => isset($jabatans[$dosenId]) ? trim($jabatans[$dosenId]) : null
                ];
            }
        }
        $surat->dosenList()->sync($syncData);

        // Sync multiple TPA recipients in pivot table with optional jabatan
        $syncDataTpa = [];
        $jabatansTpa = $request->input('jabatan_tpa', []);
        if ($request->filled('tpa_ids')) {
            foreach ($request->tpa_ids as $tpaId) {
                $syncDataTpa[$tpaId] = [
                    'jabatan' => isset($jabatansTpa[$tpaId]) ? trim($jabatansTpa[$tpaId]) : null
                ];
            }
        }
        $surat->tpaList()->sync($syncDataTpa);

        \App\Models\Notification::sendToAll('Perubahan Data', "Data {$surat->jenis_surat} nomor {$surat->nomor_surat} telah diperbarui", route('manajemen-dosen.surat.show', $surat->id));

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
        $nomorSurat = $surat->nomor_surat;
        $surat->delete();

        \App\Models\Notification::sendToAll('Perubahan Data', "{$jenisSurat} nomor {$nomorSurat} telah dihapus");

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

    /**
     * Export Excel & CSV
     */
    public function exportExcel(Request $request)
    {
        $this->authorize('kelola-data-dosen.view');

        $format = $request->get('format', 'xlsx');
        $fileName = 'data-surat-dosen-' . date('Y-m-d') . '.' . $format;

        $query = SuratDosen::with(['dosen', 'dosenList']);
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('dosen_id')) {
            $dosenId = $request->dosen_id;
            $query->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->orWhereHas('dosenList', function ($qd) use ($dosenId) {
                      $qd->where('dosen.id', $dosenId);
                  });
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', '%' . $search . '%')
                  ->orWhere('judul_surat', 'like', '%' . $search . '%')
                  ->orWhereHas('dosen', function ($qd) use ($search) {
                      $qd->where('nama_lengkap', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('dosenList', function ($qd) use ($search) {
                      $qd->where('nama_lengkap', 'like', '%' . $search . '%');
                  });
            });
        }

        $data = $query->orderBy('tanggal_surat', 'desc')->get()->map(function ($s) {
            $recipients = $s->dosenList->count() > 0 ? $s->dosenList->pluck('nama_lengkap')->implode(', ') : ($s->dosen->nama_lengkap ?? '-');
            return [
                'Jenis Surat' => $s->jenis_surat,
                'Nomor Surat' => $s->nomor_surat,
                'Judul / Perihal' => $s->judul_surat,
                'Dosen Penerima' => $recipients,
                'Tanggal Terbit' => $s->tanggal_surat ? $s->tanggal_surat->format('d-m-Y') : '-',
                'Masa Berlaku' => ($s->berlaku_mulai ? $s->berlaku_mulai->format('d/m/Y') : 'Awal') . ' s/d ' . ($s->berlaku_selesai ? $s->berlaku_selesai->format('d/m/Y') : 'Selesai'),
                'Kategori' => $s->kategori,
                'Keterangan' => $s->keterangan ?? '-',
            ];
        });

        return Excel::download(
            new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array {
                    return ['Jenis Surat', 'Nomor Surat', 'Judul / Perihal', 'Dosen Penerima', 'Tanggal Terbit', 'Masa Berlaku', 'Kategori', 'Keterangan'];
                }
            },
            $fileName
        );
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $this->authorize('kelola-data-dosen.view');

        $query = SuratDosen::with(['dosen', 'dosenList']);
        if ($request->filled('jenis_surat')) $query->where('jenis_surat', $request->jenis_surat);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('dosen_id')) {
            $dosenId = $request->dosen_id;
            $query->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)->orWhereHas('dosenList', function ($qd) use ($dosenId) { $qd->where('dosen.id', $dosenId); });
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', '%' . $search . '%')->orWhere('judul_surat', 'like', '%' . $search . '%');
            });
        }

        $surat = $query->orderBy('tanggal_surat', 'desc')->get();

        $html = '
        <h2 style="text-align: center; margin-bottom: 5px;">DATA SURAT TUGAS & SURAT KEPUTUSAN DOSEN</h2>
        <p style="text-align: center; font-size: 11px; margin-top: 0; color: #555;">Tanggal Cetak: ' . date('d-m-Y') . '</p>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">
            <thead>
                <tr style="background-color: #C41E3A; color: white;">
                    <th width="4%">No</th>
                    <th width="12%">Jenis</th>
                    <th width="20%">Nomor Surat</th>
                    <th width="24%">Judul / Perihal</th>
                    <th width="22%">Dosen Penerima</th>
                    <th width="10%">Tanggal</th>
                    <th width="8%">Kategori</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($surat as $index => $s) {
            $recipients = $s->dosenList->count() > 0 ? $s->dosenList->pluck('nama_lengkap')->implode(', ') : ($s->dosen->nama_lengkap ?? '-');
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . $s->jenis_surat . '</td>
                    <td>' . $s->nomor_surat . '</td>
                    <td>' . $s->judul_surat . '</td>
                    <td>' . $recipients . '</td>
                    <td>' . ($s->tanggal_surat ? $s->tanggal_surat->format('d/m/Y') : '-') . '</td>
                    <td>' . $s->kategori . '</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('data-surat-dosen-' . date('Y-m-d-His') . '.pdf');
    }

    /**
     * Display dashboard stats for Surat Tugas and SK.
     */
    public function dashboard()
    {
        $this->authorize('kelola-data-dosen.view');

        // Total Surat Tugas
        $totalST = SuratDosen::where('jenis_surat', 'Surat Tugas')->count();

        // Total Surat Keputusan
        $totalSK = SuratDosen::where('jenis_surat', 'Surat Keputusan')->count();

        // Total Dosen Penerima (distinct lecturer IDs in pivot table)
        $totalDosenPenerima = \DB::table('dosen_surat')->distinct('dosen_id')->count('dosen_id');

        // Surat terbit bulan ini
        $suratBulanIni = SuratDosen::whereMonth('tanggal_surat', now()->month)
            ->whereYear('tanggal_surat', now()->year)
            ->count();

        // Surat terbit per bulan (trend chart data for current year)
        $currentYear = now()->year;
        $monthlyStats = SuratDosen::select(\DB::raw('MONTH(tanggal_surat) as month'), \DB::raw('jenis_surat'), \DB::raw('count(*) as count'))
            ->whereYear('tanggal_surat', $currentYear)
            ->groupBy(\DB::raw('MONTH(tanggal_surat)'), 'jenis_surat')
            ->get();

        // Initialize months array (1-12)
        $stMonthly = array_fill(1, 12, 0);
        $skMonthly = array_fill(1, 12, 0);

        foreach ($monthlyStats as $stat) {
            if ($stat->jenis_surat === 'Surat Tugas') {
                $stMonthly[$stat->month] = $stat->count;
            } else {
                $skMonthly[$stat->month] = $stat->count;
            }
        }

        // Convert key-value (1-12) to sequential array for JS charts
        $stMonthlyArray = array_values($stMonthly);
        $skMonthlyArray = array_values($skMonthly);

        // Distribution of Surat by Category/Perihal
        $categoryStats = SuratDosen::select('kategori', \DB::raw('count(*) as count'))
            ->groupBy('kategori')
            ->orderBy('count', 'desc')
            ->get();

        // Lecturer recipients statistics (by kode_dosen)
        $dosenStats = \DB::table('dosen_surat')
            ->join('dosen', 'dosen_surat.dosen_id', '=', 'dosen.id')
            ->select('dosen.kode_dosen', \DB::raw('COUNT(*) as count'))
            ->groupBy('dosen.kode_dosen')
            ->orderBy('count', 'desc')
            ->get();

        // Recent letters
        $recentSurat = SuratDosen::with(['dosenList'])
            ->orderBy('tanggal_surat', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('manajemen-dosen.surat.dashboard', compact(
            'totalST',
            'totalSK',
            'totalDosenPenerima',
            'suratBulanIni',
            'stMonthlyArray',
            'skMonthlyArray',
            'categoryStats',
            'dosenStats',
            'recentSurat'
        ));
    }
}
