<?php

namespace App\Http\Controllers;

use App\Models\CalonDosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekrutasiDosenExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RekrutasiDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonDosen::with(['prodi', 'tahunAjar']);

        // Filter by Prodi
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }

        // Filter by Jenjang
        if ($request->filled('jenjang')) {
            $query->whereHas('prodi', function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            });
        }

        // Filter by Tahun Ajar
        if ($request->filled('tahun_ajar')) {
            $query->where('tahun_ajar_id', $request->tahun_ajar);
        }

        // Filter by Status Penerimaan
        if ($request->filled('status')) {
            $query->where('status_penerimaan', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        $allowedSorts = ['no_registrasi', 'nama', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->latest('created_at');
        }

        $rekrutasi = $query->paginate(10)->withQueryString();

        // Get filter data dari database
        $filterData = [
            'prodi' => Prodi::all(),
            'jenjang' => Prodi::distinct()->pluck('jenjang')->filter()->sort()->values(),
            'tahun_ajar' => \App\Models\TahunAjar::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get(),
            'status' => \App\Models\CalonDosen::getStatusOptions(),
        ];

        return view('rekrutasi-dosen.rekrutasi-dosen', compact('rekrutasi', 'filterData'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        // Ambil tahun ajar dari database
        $tahunAjar = \App\Models\TahunAjar::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();

        return view('rekrutasi-dosen.tambah-rekrutasi-dosen', compact('prodi', 'tahunAjar'));
    }

    public function store(Request $request)
    {
        try {
            // Debug: Log request data
            Log::info('Store request data:', [
                'all' => $request->all(),
                'files' => $request->allFiles(),
                'has_riwayat' => $request->has('riwayat')
            ]);

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'prodi_id' => 'required|exists:prodi,id',
                'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
                'status_penerimaan' => 'required|in:Seleksi,Diterima,Ditolak',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'nullable|string',
                'tanggal_lahir' => 'nullable|date',
                'nomor_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
                'jabatan_fungsional_akademik' => 'nullable|string',
                'bidang_keahlian' => 'nullable|string',
                'jalur_lamaran' => 'nullable|in:S3 Prof Full time,S2 Praktisi Part time,Praktisi Part time,Prof Full time,S3 OnGoing',
                'h_index' => 'nullable|numeric|min:0',
                // Validasi S1 (wajib)
                'riwayat.s1.nama_universitas' => 'required|string|max:255',
                'riwayat.s1.prodi_pendidikan' => 'required|string|max:255',
                'riwayat.s1.tanggal_lulus' => 'required|date',
                'riwayat.s1.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s1.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                // Validasi S2 dan S3 (opsional)
                'riwayat.s2.nama_universitas' => 'nullable|string|max:255',
                'riwayat.s2.prodi_pendidikan' => 'nullable|string|max:255',
                'riwayat.s2.tanggal_lulus' => 'nullable|date',
                'riwayat.s2.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s2.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s3.nama_universitas' => 'nullable|string|max:255',
                'riwayat.s3.prodi_pendidikan' => 'nullable|string|max:255',
                'riwayat.s3.tanggal_lulus' => 'nullable|date',
                'riwayat.s3.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s3.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // No registrasi auto-generate via model boot
            $calonDosen = CalonDosen::create($validated);
            
            Log::info('Calon dosen created:', ['id' => $calonDosen->id]);

            // Simpan riwayat pendidikan jika ada
            if ($request->has('riwayat')) {
                Log::info('Processing riwayat...', ['riwayat' => $request->riwayat]);
                
                foreach ($request->riwayat as $jenjang => $data) {
                    Log::info("Processing jenjang: {$jenjang}", ['data' => $data]);
                    
                    // Skip jika data kosong
                    if (empty($data['nama_universitas']) && empty($data['prodi_pendidikan'])) {
                        Log::info("Skipping empty jenjang: {$jenjang}");
                        continue;
                    }

                    $riwayatData = [
                        'calon_dosen_id' => $calonDosen->id,
                        'jenjang' => strtoupper($jenjang), // s1 -> S1
                        'nama_universitas' => $data['nama_universitas'] ?? null,
                        'prodi_pendidikan' => $data['prodi_pendidikan'] ?? null,
                        'tanggal_lulus' => $data['tanggal_lulus'] ?? null,
                    ];

                    // Handle file upload ijazah
                    if ($request->hasFile("riwayat.{$jenjang}.ijazah")) {
                        $ijazah = $request->file("riwayat.{$jenjang}.ijazah");
                        $ijazahName = time() . '_' . $jenjang . '_ijazah.' . $ijazah->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('riwayat_pendidikan', $ijazah, $ijazahName);
                        $riwayatData['ijazah'] = 'riwayat_pendidikan/' . $ijazahName;
                        Log::info("Ijazah uploaded: {$ijazahName}");
                    }

                    // Handle file upload transkrip
                    if ($request->hasFile("riwayat.{$jenjang}.transkrip_nilai")) {
                        $transkrip = $request->file("riwayat.{$jenjang}.transkrip_nilai");
                        $transkripName = time() . '_' . $jenjang . '_transkrip.' . $transkrip->getClientOriginalExtension();
                    Storage::disk('public')->putFileAs('riwayat_pendidikan', $transkrip, $transkripName);
                        $riwayatData['transkrip_nilai'] = 'riwayat_pendidikan/' . $transkripName;
                        Log::info("Transkrip uploaded: {$transkripName}");
                    }

                    $riwayatRecord = \App\Models\RiwayatPendidikanCalonDosen::create($riwayatData);
                    Log::info("Riwayat created:", ['id' => $riwayatRecord->id, 'data' => $riwayatData]);
                }
            } else {
                Log::info('No riwayat data in request');
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data rekrutasi berhasil ditambahkan!',
                    'data' => $calonDosen->load(['prodi', 'tahunAjar', 'riwayatPendidikan'])
                ]);
            }

            return redirect()->route('rekrutasi-dosen')
                ->with('success', 'Data rekrutasi berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Store error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $rekrutasi = CalonDosen::with(['prodi', 'tahunAjar', 'riwayatPendidikan', 'jadwalPengujian.dosenPenguji'])->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $rekrutasi
            ]);
        }

        return view('rekrutasi-dosen.detail-rekrutasi-dosen', compact('rekrutasi'));
    }

    public function edit($id)
    {
        $rekrutasi = CalonDosen::findOrFail($id);
        $prodi = Prodi::all();
        // Ambil tahun ajar dari database
        $tahunAjar = \App\Models\TahunAjar::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();

        return view('rekrutasi-dosen.edit-rekrutasi-dosen', compact('rekrutasi', 'prodi', 'tahunAjar'));
    }

    public function update(Request $request, $id)
    {
        try {
            $rekrutasi = CalonDosen::findOrFail($id);

            Log::info('Update request data:', [
                'id' => $id,
                'all' => $request->all(),
                'files' => $request->allFiles(),
                'has_riwayat' => $request->has('riwayat')
            ]);

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'prodi_id' => 'required|exists:prodi,id',
                'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
                'status_penerimaan' => 'required|in:Seleksi,Diterima,Ditolak',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'nullable|string',
                'tanggal_lahir' => 'nullable|date',
                'nomor_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
                'jabatan_fungsional_akademik' => 'nullable|string',
                'bidang_keahlian' => 'nullable|string',
                'jalur_lamaran' => 'nullable|in:S3 Prof Full time,S2 Praktisi Part time,S3 Praktisi Part time,S2 Prof Full time,S3 OnGoing',
                'h_index' => 'nullable|numeric|min:0',
                // Validasi S1 (wajib)
                'riwayat.s1.nama_universitas' => 'required|string|max:255',
                'riwayat.s1.prodi_pendidikan' => 'required|string|max:255',
                'riwayat.s1.tanggal_lulus' => 'required|date',
                'riwayat.s1.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s1.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                // Validasi S2 dan S3 (opsional)
                'riwayat.s2.nama_universitas' => 'nullable|string|max:255',
                'riwayat.s2.prodi_pendidikan' => 'nullable|string|max:255',
                'riwayat.s2.tanggal_lulus' => 'nullable|date',
                'riwayat.s2.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s2.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s3.nama_universitas' => 'nullable|string|max:255',
                'riwayat.s3.prodi_pendidikan' => 'nullable|string|max:255',
                'riwayat.s3.tanggal_lulus' => 'nullable|date',
                'riwayat.s3.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'riwayat.s3.transkrip_nilai' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $rekrutasi->update($validated);

            // Update riwayat pendidikan
            if ($request->has('riwayat')) {
                Log::info('Updating riwayat...', ['riwayat' => $request->riwayat]);
                
                foreach ($request->riwayat as $jenjang => $data) {
                    Log::info("Processing jenjang: {$jenjang}", ['data' => $data]);
                    
                    // Skip jika data kosong (untuk S2 dan S3)
                    if (strtolower($jenjang) !== 's1' && empty($data['nama_universitas']) && empty($data['prodi_pendidikan'])) {
                        // Hapus riwayat jika ada sebelumnya tapi sekarang dikosongkan
                        $existingRiwayat = $rekrutasi->riwayatPendidikan()->where('jenjang', strtoupper($jenjang))->first();
                        if ($existingRiwayat) {
                            // Hapus file lama
                            if ($existingRiwayat->ijazah && Storage::exists('public/' . $existingRiwayat->ijazah)) {
                                Storage::delete('public/' . $existingRiwayat->ijazah);
                            }
                            if ($existingRiwayat->transkrip_nilai && Storage::exists('public/' . $existingRiwayat->transkrip_nilai)) {
                                Storage::delete('public/' . $existingRiwayat->transkrip_nilai);
                            }
                            $existingRiwayat->delete();
                            Log::info("Deleted empty jenjang: {$jenjang}");
                        }
                        continue;
                    }

                    $riwayatData = [
                        'calon_dosen_id' => $rekrutasi->id,
                        'jenjang' => strtoupper($jenjang),
                        'nama_universitas' => $data['nama_universitas'] ?? null,
                        'prodi_pendidikan' => $data['prodi_pendidikan'] ?? null,
                        'tanggal_lulus' => $data['tanggal_lulus'] ?? null,
                    ];

                    // Cari riwayat yang sudah ada
                    $existingRiwayat = $rekrutasi->riwayatPendidikan()->where('jenjang', strtoupper($jenjang))->first();

                    // Handle file upload ijazah (hanya jika ada file baru)
                    if ($request->hasFile("riwayat.{$jenjang}.ijazah")) {
                        $ijazah = $request->file("riwayat.{$jenjang}.ijazah");
                        $ijazahName = time() . '_' . $jenjang . '_ijazah.' . $ijazah->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('riwayat_pendidikan', $ijazah, $ijazahName);
                        $riwayatData['ijazah'] = 'riwayat_pendidikan/' . $ijazahName;
                        
                        // Hapus file lama jika ada
                        if ($existingRiwayat && $existingRiwayat->ijazah && Storage::exists('public/' . $existingRiwayat->ijazah)) {
                            Storage::delete('public/' . $existingRiwayat->ijazah);
                        }
                        Log::info("Ijazah uploaded: {$ijazahName}");
                    } elseif ($existingRiwayat) {
                        // Pertahankan file lama jika tidak ada file baru
                        $riwayatData['ijazah'] = $existingRiwayat->ijazah;
                    }

                    // Handle file upload transkrip (hanya jika ada file baru)
                    if ($request->hasFile("riwayat.{$jenjang}.transkrip_nilai")) {
                        $transkrip = $request->file("riwayat.{$jenjang}.transkrip_nilai");
                        $transkripName = time() . '_' . $jenjang . '_transkrip.' . $transkrip->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('riwayat_pendidikan', $transkrip, $transkripName);
                        $riwayatData['transkrip_nilai'] = 'riwayat_pendidikan/' . $transkripName;
                        
                        // Hapus file lama jika ada
                        if ($existingRiwayat && $existingRiwayat->transkrip_nilai && Storage::exists('public/' . $existingRiwayat->transkrip_nilai)) {
                            Storage::delete('public/' . $existingRiwayat->transkrip_nilai);
                        }
                        Log::info("Transkrip uploaded: {$transkripName}");
                    } elseif ($existingRiwayat) {
                        // Pertahankan file lama jika tidak ada file baru
                        $riwayatData['transkrip_nilai'] = $existingRiwayat->transkrip_nilai;
                    }

                    // Update atau create
                    if ($existingRiwayat) {
                        $existingRiwayat->update($riwayatData);
                        Log::info("Riwayat updated:", ['id' => $existingRiwayat->id, 'data' => $riwayatData]);
                    } else {
                        $riwayatRecord = \App\Models\RiwayatPendidikanCalonDosen::create($riwayatData);
                        Log::info("Riwayat created:", ['id' => $riwayatRecord->id, 'data' => $riwayatData]);
                    }
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data rekrutasi berhasil diupdate!',
                    'data' => $rekrutasi->load(['prodi', 'tahunAjar', 'riwayatPendidikan'])
                ]);
            }

            return redirect()->route('rekrutasi-dosen')
                ->with('success', 'Data rekrutasi berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Update validation error:', ['errors' => $e->errors()]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Update error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $rekrutasi = CalonDosen::findOrFail($id);
        $rekrutasi->delete();

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil dihapus!');
    }

    public function importView(Request $request)
    {
        // Get current step from request
        $step = $request->get('step');
        
        // If step is not specified, determine based on session
        if (!$step) {
            if (session()->has('import_result')) {
                // If result exists, redirect to result page
                return redirect()->route('rekrutasi-dosen.import.result');
            } elseif (session()->has('import_data')) {
                // If import data exists, go to step 2
                $step = 2;
            } else {
                // Default to step 1
                $step = 1;
            }
        }
        
        // If navigating to step 1, clear import data but keep import_result if exists
        if ($step == 1 && $request->has('step')) {
            session()->forget(['import_data', 'show_import']);
        }
        
        // Clear all import sessions if reset is requested
        if ($request->get('reset') == '1') {
            session()->forget(['import_data', 'show_import', 'import_result', 'file_uploaded']);
        }

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

    public function jadwalPengujian(Request $request)
    {
        $query = \App\Models\JadwalPengujian::with(['calonDosen', 'dosenPenguji', 'tahunAjar']);

        // Apply filters
        if ($request->filled('metode')) {
            $query->where('metode_pelaksanaan', $request->metode);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('calonDosen', function($sq) use ($searchTerm) {
                    $sq->where('nama', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('dosenPenguji', function($sq) use ($searchTerm) {
                    $sq->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        $jadwalList = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Get metode pelaksanaan options (enum values)
        $metodeList = ['Online', 'Onsite'];

        // Get data for modals
        $calonDosenList = \App\Models\CalonDosen::all();
        $dosenList = \App\Models\Dosen::all();
        $tahunAjarList = \App\Models\TahunAjar::all();

        return view('rekrutasi-dosen.jadwal-pengujian', compact('jadwalList', 'metodeList', 'calonDosenList', 'dosenList', 'tahunAjarList'));
    }

    public function storeJadwalPengujian(Request $request)
    {
        try {
            $validated = $request->validate([
                'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
                'calon_dosen_id' => 'required|exists:calon_dosen,id',
                'dosen_penguji_id' => 'required|array|min:2|max:3',
                'dosen_penguji_id.*' => 'required|exists:dosen,id|distinct',
                'jadwal_ujian' => 'required|date',
                'metode_pelaksanaan' => 'required|in:Online,Onsite',
                'gedung' => 'nullable|string',
                'ruangan' => 'nullable|string',
                'waktu' => 'required',
            ]);

            // Set gedung and ruangan to null if metode is Online
            if ($validated['metode_pelaksanaan'] === 'Online') {
                $validated['gedung'] = null;
                $validated['ruangan'] = null;
            }

            // Extract dosen_penguji_id before creating
            $dosenPengujiIds = $validated['dosen_penguji_id'];
            unset($validated['dosen_penguji_id']);

            $jadwal = \App\Models\JadwalPengujian::create($validated);

            // Attach dosen penguji with urutan
            $dosenData = [];
            foreach ($dosenPengujiIds as $index => $dosenId) {
                $dosenData[$dosenId] = ['urutan' => $index + 1];
            }
            $jadwal->dosenPenguji()->attach($dosenData);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pengujian berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal pengujian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showJadwalPengujian($id)
    {
        $jadwal = \App\Models\JadwalPengujian::with(['calonDosen', 'dosenPenguji', 'tahunAjar'])->findOrFail($id);

        // Format dosen penguji array
        $dosenPengujiList = $jadwal->dosenPenguji->map(function($dosen) {
            return [
                'id' => $dosen->id,
                'urutan' => $dosen->pivot->urutan,
                'nama' => $dosen->front_title . ' ' . $dosen->nama_lengkap . ', ' . $dosen->back_title
            ];
        })->sortBy('urutan')->values();

        return response()->json([
            'tahun_ajar_id' => $jadwal->tahun_ajar_id,
            'calon_dosen_id' => $jadwal->calon_dosen_id,
            'dosen_penguji_ids' => $jadwal->dosenPenguji->pluck('id')->toArray(),
            'dosen_penguji_list' => $dosenPengujiList,
            'calon_dosen_nama' => $jadwal->calonDosen->nama,
            'tahun_ajar' => $jadwal->tahunAjar->label,
            'jadwal_ujian' => \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d F Y'),
            'jadwal_ujian_raw' => \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('Y-m-d'),
            'metode_pelaksanaan' => $jadwal->metode_pelaksanaan,
            'gedung' => $jadwal->gedung,
            'ruangan' => $jadwal->ruangan,
            'waktu' => \Carbon\Carbon::parse($jadwal->waktu)->format('H:i'),
            'waktu_raw' => \Carbon\Carbon::parse($jadwal->waktu)->format('H:i'),
        ]);
    }

    public function editJadwalPengujian($id)
    {
        $jadwal = \App\Models\JadwalPengujian::findOrFail($id);
        $calonDosenList = \App\Models\CalonDosen::all();
        $dosenList = \App\Models\Dosen::all();
        $tahunAjarList = \App\Models\TahunAjar::all();

        return view('rekrutasi-dosen.jadwal-pengujian-edit', compact('jadwal', 'calonDosenList', 'dosenList', 'tahunAjarList'));
    }

    public function updateJadwalPengujian(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
                'calon_dosen_id' => 'required|exists:calon_dosen,id',
                'dosen_penguji_id' => 'required|array|min:2|max:3',
                'dosen_penguji_id.*' => 'required|exists:dosen,id|distinct',
                'jadwal_ujian' => 'required|date',
                'metode_pelaksanaan' => 'required|in:Online,Onsite',
                'gedung' => 'nullable|string',
                'ruangan' => 'nullable|string',
                'waktu' => 'required',
            ]);

            // Set gedung and ruangan to null if metode is Online
            if ($validated['metode_pelaksanaan'] === 'Online') {
                $validated['gedung'] = null;
                $validated['ruangan'] = null;
            }

            $jadwal = \App\Models\JadwalPengujian::findOrFail($id);

            // Extract dosen_penguji_id before updating
            $dosenPengujiIds = $validated['dosen_penguji_id'];
            unset($validated['dosen_penguji_id']);

            $jadwal->update($validated);

            // Sync dosen penguji with urutan
            $dosenData = [];
            foreach ($dosenPengujiIds as $index => $dosenId) {
                $dosenData[$dosenId] = ['urutan' => $index + 1];
            }
            $jadwal->dosenPenguji()->sync($dosenData);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pengujian berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating jadwal pengujian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jadwal pengujian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyJadwalPengujian($id)
    {
        try {
            $jadwal = \App\Models\JadwalPengujian::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal pengujian berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting jadwal pengujian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal pengujian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function penilaian($jadwal_id)
    {
        try {
            // Check authorization
            /** @var User $user */
            $user = Auth::user();
            if (!$user->hasRole(['Super Admin', 'Dosen Penguji 1', 'Dosen Penguji 2', 'Dosen Penguji 3'])) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            $jadwal = \App\Models\JadwalPengujian::with([
                'calonDosen.prodi',
                'dosenPenguji',
                'tahunAjar'
            ])->findOrFail($jadwal_id);

            $calonDosen = $jadwal->calonDosen;

            // Check if there's existing penilaian by current user
            $existingPenilaian = \App\Models\PenilaianDetail::where('jadwal_pengujian_id', $jadwal_id)
                ->where('user_id', Auth::id())
                ->first();

            return view('rekrutasi-dosen.penilaian-calon-dosen', compact('jadwal', 'calonDosen', 'existingPenilaian'));
        } catch (\Exception $e) {
            Log::error('Error loading penilaian page: ' . $e->getMessage());
            return redirect()->route('jadwal-pengujian')
                ->with('error', 'Gagal memuat halaman penilaian: ' . $e->getMessage());
        }
    }

    public function storePenilaian(Request $request)
    {
        try {
            Log::info('Store penilaian request:', $request->all());

            // Validate request
            $validated = $request->validate([
                'jadwal_pengujian_id' => 'required|exists:jadwal_pengujian,id',
                'calon_dosen_id' => 'required|exists:calon_dosen,id',
                'nilai_jalur_lamaran' => 'required|numeric|min:1|max:5',
                'nilai_jfa' => 'required|numeric|min:1|max:5',
                'nilai_h_index' => 'required|numeric|min:1|max:5',
                'rata_a' => 'required|numeric',
                'nilai_pma' => 'required|numeric|min:1|max:5',
                'nilai_sistematika' => 'required|numeric|min:1|max:5',
                'nilai_kst' => 'required|numeric|min:1|max:5',
                'rata_b' => 'required|numeric',
                'nilai_motivasi' => 'required|numeric|min:1|max:5',
                'nilai_kmp_mengajar' => 'required|numeric|min:1|max:5',
                'nilai_kmp_mkp' => 'required|numeric|min:1|max:5',
                'nilai_kmp_pp' => 'required|numeric|min:1|max:5',
                'nilai_kmp_abdimas' => 'required|numeric|min:1|max:5',
                'nilai_kmp_bdt' => 'required|numeric|min:1|max:5',
                'nilai_keahlian_lainnya' => 'required|numeric|min:1|max:5',
                'nilai_kmt_wkm' => 'required|numeric|min:1|max:5',
                'rata_c' => 'required|numeric',
                'rata_nilai' => 'required|numeric',
                'keterangan_berbobot' => 'required|string',
                'kesiapan' => 'required|in:YA,TIDAK/PIKIR-PIKIR',
                'kesediaan' => 'required|in:YA,TIDAK/PIKIR-PIKIR',
                'catatan_penilai' => 'nullable|string',
            ]);

            // Get authenticated dosen from current logged in user
            $jadwal = \App\Models\JadwalPengujian::with('dosenPenguji')->findOrFail($validated['jadwal_pengujian_id']);
            
            // Get dosen_id from current logged in user
            $currentDosen = \App\Models\Dosen::where('user_id', Auth::id())->first();
            $dosenId = $currentDosen ? $currentDosen->id : null;
            
            if (!$dosenId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan untuk user yang login'
                ], 400);
            }

            // Get calon dosen data
            $calonDosen = \App\Models\CalonDosen::findOrFail($validated['calon_dosen_id']);

            // Convert kesiapan and kesediaan from YA/TIDAK to boolean
            $kesiapan = $validated['kesiapan'] === 'YA' ? true : false;
            $kesediaan = $validated['kesediaan'] === 'YA' ? true : false;

            // Check if penilaian already exists (by user_id)
            $existingPenilaian = \App\Models\PenilaianDetail::where('jadwal_pengujian_id', $validated['jadwal_pengujian_id'])
                ->where('user_id', Auth::id())
                ->first();

            $penilaianData = [
                'dosen_id' => $dosenId,
                'user_id' => Auth::id(),
                'calon_dosen_id' => $validated['calon_dosen_id'],
                'jadwal_pengujian_id' => $validated['jadwal_pengujian_id'],
                'nilai_jalur_lamaran' => $validated['nilai_jalur_lamaran'],
                'nilai_jfa' => $validated['nilai_jfa'],
                'nilai_h_index' => $validated['nilai_h_index'],
                'rata_a' => $validated['rata_a'],
                'nilai_pma' => $validated['nilai_pma'],
                'nilai_sistematika' => $validated['nilai_sistematika'],
                'nilai_kst' => $validated['nilai_kst'],
                'rata_b' => $validated['rata_b'],
                'nilai_motivasi' => $validated['nilai_motivasi'],
                'nilai_kmp_mengajar' => $validated['nilai_kmp_mengajar'],
                'nilai_kmp_mkp' => $validated['nilai_kmp_mkp'],
                'nilai_kmp_pp' => $validated['nilai_kmp_pp'],
                'nilai_kmp_abdimas' => $validated['nilai_kmp_abdimas'],
                'nilai_kmp_bdt' => $validated['nilai_kmp_bdt'],
                'nilai_keahlian_lainnya' => $validated['nilai_keahlian_lainnya'],
                'nilai_kmt_wkm' => $validated['nilai_kmt_wkm'],
                'rata_c' => $validated['rata_c'],
                'rata_nilai' => $validated['rata_nilai'],
                'keterangan_berbobot' => $validated['keterangan_berbobot'],
                'kesiapan' => $kesiapan,
                'kesediaan' => $kesediaan,
                'catatan_penilai' => $validated['catatan_penilai'],
            ];

            if ($existingPenilaian) {
                // Update existing penilaian
                $existingPenilaian->update($penilaianData);
                $penilaian = $existingPenilaian;
                Log::info('Penilaian updated successfully:', ['id' => $penilaian->id]);
            } else {
                // Create new penilaian
                $penilaian = \App\Models\PenilaianDetail::create($penilaianData);
                Log::info('Penilaian created successfully:', ['id' => $penilaian->id]);
            }


            return response()->json([
                'success' => true,
                'message' => 'Penilaian berhasil disimpan',
                'data' => $penilaian
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing penilaian: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPenilaianExcel($penilaianId)
    {
        try {
            Log::info('Export penilaian called', ['penilaian_id' => $penilaianId, 'user_id' => Auth::id()]);
            
            // Get penilaian detail
            $penilaian = \App\Models\PenilaianDetail::with(['calonDosen', 'jadwal.tahunAjar', 'dosen', 'user'])
                ->findOrFail($penilaianId);

            Log::info('Penilaian found', ['penilaian' => $penilaian->id]);

            // Authorization check - only owner or Super Admin
            /** @var User $user */
            $user = Auth::user();
            if (Auth::id() !== $penilaian->user_id && !$user->hasRole('Super Admin')) {
                Log::warning('Unauthorized export attempt', ['user_id' => Auth::id(), 'penilaian_user_id' => $penilaian->user_id]);
                abort(403, 'Anda tidak memiliki akses untuk mengunduh penilaian ini');
            }

            // Get dosen penguji data
            $dosenPenguji = \App\Models\Dosen::where('user_id', $penilaian->user_id)->first();
            
            if (!$dosenPenguji) {
                Log::error('Dosen penguji not found', ['user_id' => $penilaian->user_id]);
                return redirect()->back()->with('error', 'Data dosen penguji tidak ditemukan');
            }

            Log::info('Dosen penguji found', ['dosen' => $dosenPenguji->nama_lengkap]);

            $calonDosenName = str_replace(' ', '_', $penilaian->calonDosen->nama);
            $filename = 'Penilaian_' . $calonDosenName . '_' . date('Ymd_His') . '.xlsx';

            Log::info('Starting Excel export', ['filename' => $filename]);

            return Excel::download(
                new \App\Exports\PenilaianCalonDosenExport($penilaianId), 
                $filename
            );

        } catch (\Exception $e) {
            Log::error('Error exporting penilaian: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh Excel: ' . $e->getMessage());
        }
    }

    public function exportPenilaianPdf($penilaianId)
    {
        try {
            // Increase memory limit for PDF generation
            ini_set('memory_limit', '256M');
            
            Log::info('Export penilaian PDF called', ['penilaian_id' => $penilaianId, 'user_id' => Auth::id()]);
            
            // Get penilaian detail
            $penilaian = \App\Models\PenilaianDetail::with(['calonDosen', 'jadwal.tahunAjar', 'dosen', 'user'])
                ->findOrFail($penilaianId);

            Log::info('Penilaian found for PDF', ['penilaian' => $penilaian->id]);

            // Authorization check - only owner or Super Admin
            /** @var User $user */
            $user = Auth::user();
            if (Auth::id() !== $penilaian->user_id && !$user->hasRole('Super Admin')) {
                Log::warning('Unauthorized PDF export attempt', ['user_id' => Auth::id(), 'penilaian_user_id' => $penilaian->user_id]);
                abort(403, 'Anda tidak memiliki akses untuk mengunduh penilaian ini');
            }

            // Get dosen penguji data
            $dosenPenguji = \App\Models\Dosen::where('user_id', $penilaian->user_id)->first();
            
            if (!$dosenPenguji) {
                Log::error('Dosen penguji not found for PDF', ['user_id' => $penilaian->user_id]);
                return redirect()->back()->with('error', 'Data dosen penguji tidak ditemukan');
            }

            Log::info('Dosen penguji found for PDF', ['dosen' => $dosenPenguji->nama_lengkap]);

            // Use PDF export class
            $pdfExport = new \App\Exports\PenilaianCalonDosenPdfExport($penilaianId);
            $data = $pdfExport->getData();

            $calonDosenName = str_replace(' ', '_', $penilaian->calonDosen->nama);
            $filename = 'Penilaian_' . $calonDosenName . '_' . date('Ymd_His') . '.pdf';

            Log::info('Starting PDF export', ['filename' => $filename]);

            // Generate PDF using DomPDF with optimized settings
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.penilaian-pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error exporting penilaian PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh PDF: ' . $e->getMessage());
        }
    }

    public function exportJadwalPengujianExcel()
    {
        $jadwalList = \App\Models\JadwalPengujian::with(['calonDosen', 'dosenPenguji', 'tahunAjar'])->get();


        $filename = 'jadwal-pengujian-' . date('Y-m-d-His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($jadwalList) {
            echo $this->generateJadwalPengujianExcel($jadwalList);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateJadwalPengujianExcel($data)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Styles>
  <Style ss:ID="Header">
   <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
   <Interior ss:Color="#C41E3A" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="Data">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="DataWrap">
   <Alignment ss:Vertical="Top" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Jadwal Pengujian">
  <Table>
   <Column ss:Width="50"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="150"/>
   <Column ss:Width="120"/>
   <Column ss:Width="120"/>
   <Column ss:Width="120"/>
   <Column ss:Width="100"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">No</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama Calon Dosen</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Dosen Penguji</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tahun Ajar</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Metode</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Gedung</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Ruangan</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Waktu</Data></Cell>
   </Row>';

        foreach ($data as $index => $jadwal) {
            // Build multiple dosen penguji with numbering (each on new line)
            $dosenPengujiList = [];
            foreach ($jadwal->dosenPenguji as $dosen) {
                $dosenPengujiList[] = $dosen->pivot->urutan . '. ' . $dosen->front_title . ' ' . $dosen->nama_lengkap . ', ' . $dosen->back_title;
            }
            // First escape XML special characters, then join with line break entity
            $dosenPengujiEscaped = array_map(function($item) {
                return htmlspecialchars($item, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            }, $dosenPengujiList);
            $dosenPengujiNama = implode('&#10;', $dosenPengujiEscaped);
            
            $waktu = \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($jadwal->waktu)->format('H:i');

            $xml .= '
   <Row>
    <Cell ss:StyleID="Data"><Data ss:Type="Number">' . ($index + 1) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($jadwal->calonDosen->nama, ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="DataWrap"><Data ss:Type="String">' . $dosenPengujiNama . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($jadwal->tahunAjar->label, ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($jadwal->metode_pelaksanaan, ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($jadwal->gedung ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($jadwal->ruangan ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($waktu, ENT_XML1, 'UTF-8') . '</Data></Cell>
   </Row>';
        }

        $xml .= '
  </Table>
 </Worksheet>
</Workbook>';

        return $xml;
    }

    public function exportJadwalPengujianCsv()
    {
        $jadwalList = \App\Models\JadwalPengujian::with(['calonDosen', 'dosenPenguji', 'tahunAjar'])->get();

        $filename = 'jadwal-pengujian-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($jadwalList) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Nama Calon Dosen', 'Dosen Penguji', 'Tahun Ajar', 'Metode', 'Gedung', 'Ruangan', 'Waktu']);
            
            // Data
            foreach ($jadwalList as $index => $jadwal) {
                // Build multiple dosen penguji with numbering
                $dosenPengujiList = [];
                foreach ($jadwal->dosenPenguji as $dosen) {
                    $dosenPengujiList[] = $dosen->pivot->urutan . '. ' . $dosen->front_title . ' ' . $dosen->nama_lengkap . ', ' . $dosen->back_title;
                }
                $dosenPengujiNama = implode(' | ', $dosenPengujiList);
                $waktu = \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($jadwal->waktu)->format('H:i');
                
                fputcsv($file, [
                    $index + 1,
                    $jadwal->calonDosen->nama,
                    $dosenPengujiNama,
                    $jadwal->tahunAjar->label,
                    $jadwal->metode_pelaksanaan,
                    $jadwal->gedung ?? '-',
                    $jadwal->ruangan ?? '-',
                    $waktu
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportJadwalPengujianPdf()
    {
        $jadwalList = \App\Models\JadwalPengujian::with(['calonDosen', 'dosenPenguji', 'tahunAjar'])->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekrutasi-dosen.jadwal-pengujian-pdf', compact('jadwalList'));
        
        return $pdf->download('jadwal-pengujian-' . date('Y-m-d-His') . '.pdf');
    }

    public function hasilPengujian()
    {
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.hasil-pengujian-dosen');
    }

    /**
     * Download template Excel (empty)
     */
    public function downloadTemplate()
    {
        $filename = 'template-rekrutasi-dosen.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            echo $this->generateTemplateExcel();
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate empty template Excel
     */
    private function generateTemplateExcel()
    {
        // Get dynamic data for dropdowns
        $tahunAjarList = \App\Models\TahunAjar::all()->pluck('label')->toArray();
        $prodiList = \App\Models\Prodi::all()->pluck('nama_prodi')->toArray();
        
        return '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Styles>
  <Style ss:ID="Header">
   <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
   <Interior ss:Color="#C41E3A" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="Instruction">
   <Font ss:Italic="1" ss:Color="#666666" ss:Size="9"/>
   <Interior ss:Color="#FFFFCC" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="Example">
   <Interior ss:Color="#E8F4F8" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Template Rekrutasi Dosen">
  <Table>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="180"/>
   <Column ss:Width="100"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jenis Kelamin</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tahun Ajar</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jalur Lamaran</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">H-Index</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S3</Data></Cell>
   </Row>
   <Row ss:Height="30">
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama lengkap calon dosen</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Laki-laki / Perempuan</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Pilih dari daftar tahun ajar</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Pilih dari daftar program studi</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Pilih: S3 Prof Full time / S2 Praktisi Part time / S3 Praktisi Part time / S2 Prof Full time / S3 OnGoing</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Angka desimal (contoh: 12 atau 8.5 atau 0.5)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama universitas S1 (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama prodi S1 (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Format: dd/mm/yyyy (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama universitas S2 (opsional)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama prodi S2 (opsional)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Format: dd/mm/yyyy (opsional)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama universitas S3 (opsional)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama prodi S3 (opsional)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Format: dd/mm/yyyy (opsional)</Data></Cell>
   </Row>
   <Row ss:Height="22">
    <Cell ss:StyleID="Example"><Data ss:Type="String">Dr. John Doe, M.Kom</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Laki-laki</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">' . ($tahunAjarList[0] ?? '2024/2025 Ganjil') . '</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">' . ($prodiList[0] ?? 'Sistem Informasi') . '</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">S2 Praktisi Part time</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">9.5</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Universitas Indonesia</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Teknik Informatika</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">15/08/2010</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Institut Teknologi Bandung</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Ilmu Komputer</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">20/09/2015</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String"></Data></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>';
    }

    /**
     * Upload and validate import file
     */
    public function uploadImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $data = $this->parseImportFile($file);

            if (empty($data)) {
                return redirect()->back()
                    ->with('error', 'File kosong atau format tidak sesuai. Pastikan file berisi data yang valid.');
            }

            // Validate data
            $validatedData = $this->validateImportData($data);

            // Store in session
            session([
                'import_data' => $validatedData, 
                'show_import' => true,
                'file_uploaded' => true
            ]);

            $validCount = collect($validatedData)->where('is_valid', true)->count();
            $totalCount = count($validatedData);

            return redirect()->route('rekrutasi-dosen.import.view', ['step' => 2])
                ->with('success', "File berhasil diupload! {$validCount} dari {$totalCount} data valid.");
        } catch (\Exception $e) {
            logger()->error('Upload error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error upload file: ' . $e->getMessage());
        }
    }

    /**
     * Parse uploaded file (Excel/CSV)
     */
    private function parseImportFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $path = $file->getRealPath();
        $data = [];

        if ($extension === 'csv') {
            $handle = fopen($path, 'r');

            // Skip header (UTF-8 BOM aware)
            $firstLine = fgets($handle);
            if (strpos($firstLine, "\xEF\xBB\xBF") === 0) {
                $firstLine = substr($firstLine, 3);
            }
            
            // Skip instruction row (second row)
            fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 15) {
                    // Include row even if fields are empty for validation
                    $hasData = false;
                    foreach (array_slice($row, 0, 7) as $cell) { // Check only required fields
                        if (!empty(trim($cell))) {
                            $hasData = true;
                            break;
                        }
                    }
                    
                    if ($hasData) {
                        $data[] = [
                            'nama_calon' => trim($row[0]),
                            'jenis_kelamin' => trim($row[1]),
                            'tahun_ajar' => trim($row[2]),
                            'prodi' => trim($row[3]),
                            // New fields (after prodi)
                            'jalur_lamaran' => trim($row[4] ?? ''),
                            'h_index' => trim($row[5] ?? ''),
                            // S1 (required)
                            'universitas_s1' => trim($row[6]),
                            'prodi_s1' => trim($row[7]),
                            'tanggal_lulus_s1' => trim($row[8]),
                            // S2 (optional)
                            'universitas_s2' => trim($row[9] ?? ''),
                            'prodi_s2' => trim($row[10] ?? ''),
                            'tanggal_lulus_s2' => trim($row[11] ?? ''),
                            // S3 (optional)
                            'universitas_s3' => trim($row[12] ?? ''),
                            'prodi_s3' => trim($row[13] ?? ''),
                            'tanggal_lulus_s3' => trim($row[14] ?? ''),
                        ];
                    }
                }
            }
            fclose($handle);
        } else {
            // Parse Excel (XLS/XLSX)
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                // Skip header row (row 1)
                array_shift($rows);
                
                // Skip instruction row (row 2)
                array_shift($rows);

                foreach ($rows as $row) {
                    if (count($row) >= 15) {
                        // Include row even if fields are empty for validation
                        $hasData = false;
                        foreach (array_slice($row, 0, 7) as $cell) { // Check only required fields
                            if (!empty(trim($cell))) {
                                $hasData = true;
                                break;
                            }
                        }
                        
                        if ($hasData) {
                            $data[] = [
                                'nama_calon' => trim($row[0] ?? ''),
                                'jenis_kelamin' => trim($row[1] ?? ''),
                                'tahun_ajar' => trim($row[2] ?? ''),
                                'prodi' => trim($row[3] ?? ''),
                                // New fields (after prodi)
                                'jalur_lamaran' => trim($row[4] ?? ''),
                                'h_index' => trim($row[5] ?? ''),
                                // S1 (required)
                                'universitas_s1' => trim($row[6] ?? ''),
                                'prodi_s1' => trim($row[7] ?? ''),
                                'tanggal_lulus_s1' => trim($row[8] ?? ''),
                                // S2 (optional)
                                'universitas_s2' => trim($row[9] ?? ''),
                                'prodi_s2' => trim($row[10] ?? ''),
                                'tanggal_lulus_s2' => trim($row[11] ?? ''),
                                // S3 (optional)
                                'universitas_s3' => trim($row[12] ?? ''),
                                'prodi_s3' => trim($row[13] ?? ''),
                                'tanggal_lulus_s3' => trim($row[14] ?? ''),
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                logger()->error('Excel parse error: ' . $e->getMessage());
            }
        }

        return $data;
    }

    /**
     * Validate import data
     */
    private function validateImportData($data)
    {
        $validated = [];

        foreach ($data as $index => $row) {
            $errors = [];

            // Validate Nama (required)
            if (empty($row['nama_calon'])) {
                $errors[] = 'Nama kosong';
            }

            // Validate Jenis Kelamin (required, enum)
            if (!in_array($row['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
                $errors[] = 'Jenis kelamin tidak valid (harus Laki-laki atau Perempuan)';
            }

            // Validate Tahun Ajar (required)
            $tahunAjarId = null;
            if (empty($row['tahun_ajar'])) {
                $errors[] = 'Tahun ajar kosong';
            } else {
                $tahunAjar = \App\Models\TahunAjar::all()->first(function($ta) use ($row) {
                    return $ta->label === $row['tahun_ajar'];
                });
                
                if ($tahunAjar) {
                    $tahunAjarId = $tahunAjar->id;
                } else {
                    $errors[] = 'Tahun ajar tidak ditemukan di database';
                }
            }

            // Validate Prodi (required)
            $prodiId = null;
            if (empty($row['prodi'])) {
                $errors[] = 'Prodi kosong';
            } else {
                $prodi = Prodi::where('nama_prodi', $row['prodi'])->first();
                if ($prodi) {
                    $prodiId = $prodi->id;
                } else {
                    $errors[] = 'Prodi tidak ditemukan di database';
                }
            }

            // Validate S1 Education (all required)
            if (empty($row['universitas_s1'])) {
                $errors[] = 'Universitas S1 wajib diisi';
            }
            if (empty($row['prodi_s1'])) {
                $errors[] = 'Program Studi S1 wajib diisi';
            }
            
            $tanggalLulusS1 = null;
            if (empty($row['tanggal_lulus_s1'])) {
                $errors[] = 'Tanggal Lulus S1 wajib diisi';
            } else {
                $tanggalLulusS1 = $this->parseDate($row['tanggal_lulus_s1']);
                if (!$tanggalLulusS1) {
                    $errors[] = 'Format Tanggal Lulus S1 tidak valid (gunakan dd/mm/yyyy)';
                }
            }

            // Validate S2 Education (optional, but if any field filled, all required)
            $hasS2Data = !empty($row['universitas_s2']) || !empty($row['prodi_s2']) || !empty($row['tanggal_lulus_s2']);
            $tanggalLulusS2 = null;
            
            if ($hasS2Data) {
                if (empty($row['universitas_s2'])) {
                    $errors[] = 'Universitas S2 harus diisi jika ada data S2 lainnya';
                }
                if (empty($row['prodi_s2'])) {
                    $errors[] = 'Program Studi S2 harus diisi jika ada data S2 lainnya';
                }
                if (empty($row['tanggal_lulus_s2'])) {
                    $errors[] = 'Tanggal Lulus S2 harus diisi jika ada data S2 lainnya';
                } else {
                    $tanggalLulusS2 = $this->parseDate($row['tanggal_lulus_s2']);
                    if (!$tanggalLulusS2) {
                        $errors[] = 'Format Tanggal Lulus S2 tidak valid (gunakan dd/mm/yyyy)';
                    }
                }
            }

            // Validate S3 Education (optional, but if any field filled, all required)
            $hasS3Data = !empty($row['universitas_s3']) || !empty($row['prodi_s3']) || !empty($row['tanggal_lulus_s3']);
            $tanggalLulusS3 = null;
            
            if ($hasS3Data) {
                if (empty($row['universitas_s3'])) {
                    $errors[] = 'Universitas S3 harus diisi jika ada data S3 lainnya';
                }
                if (empty($row['prodi_s3'])) {
                    $errors[] = 'Program Studi S3 harus diisi jika ada data S3 lainnya';
                }
                if (empty($row['tanggal_lulus_s3'])) {
                    $errors[] = 'Tanggal Lulus S3 harus diisi jika ada data S3 lainnya';
                } else {
                    $tanggalLulusS3 = $this->parseDate($row['tanggal_lulus_s3']);
                    if (!$tanggalLulusS3) {
                        $errors[] = 'Format Tanggal Lulus S3 tidak valid (gunakan dd/mm/yyyy)';
                    }
                }
            }

            // Validate Jalur Lamaran (optional, enum)
            $jalurLamaran = null;
            if (!empty($row['jalur_lamaran'])) {
                $validJalur = ['S3 Prof Full time', 'S2 Praktisi Part time', 'S3 Praktisi Part time', 'S2 Prof Full time', 'S3 OnGoing'];
                if (in_array($row['jalur_lamaran'], $validJalur)) {
                    $jalurLamaran = $row['jalur_lamaran'];
                } else {
                    $errors[] = 'Jalur lamaran tidak valid (pilihan: S3 Prof Full time, S2 Praktisi Part time, S3 Praktisi Part time, S2 Prof Full time, S3 OnGoing)';
                }
            }

            // Validate H-Index (optional, numeric)
            $hIndex = null;
            if (!empty($row['h_index'])) {
                if (is_numeric($row['h_index']) && $row['h_index'] >= 0) {
                    $hIndex = (float) $row['h_index'];
                } else {
                    $errors[] = 'H-Index harus berupa angka >= 0 (contoh: 12, 8.5, 0.5)';
                }
            }

            $validated[] = [
                'nama_calon' => $row['nama_calon'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tahun_ajar' => $row['tahun_ajar'],
                'tahun_ajar_id' => $tahunAjarId,
                'prodi_name' => $row['prodi'],
                'prodi_id' => $prodiId,
                // S1 Education
                'universitas_s1' => $row['universitas_s1'],
                'prodi_s1' => $row['prodi_s1'],
                'tanggal_lulus_s1' => $tanggalLulusS1,
                // S2 Education
                'has_s2' => $hasS2Data,
                'universitas_s2' => $row['universitas_s2'],
                'prodi_s2' => $row['prodi_s2'],
                'tanggal_lulus_s2' => $tanggalLulusS2,
                // S3 Education
                'has_s3' => $hasS3Data,
                'universitas_s3' => $row['universitas_s3'],
                'prodi_s3' => $row['prodi_s3'],
                'tanggal_lulus_s3' => $tanggalLulusS3,
                // New fields
                'jalur_lamaran' => $jalurLamaran,
                'h_index' => $hIndex,
                // Validation status
                'is_valid' => empty($errors),
                'errors' => $errors
            ];
        }

        return $validated;
    }

    /**
     * Parse date from dd/mm/yyyy format to Y-m-d
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Try dd/mm/yyyy format (slash separator)
        $parts = explode('/', $dateString);
        if (count($parts) === 3) {
            $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            $month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
            $year = $parts[2];
            
            // Validate date
            if (checkdate($month, $day, $year)) {
                return "{$year}-{$month}-{$day}";
            }
        }

        return null;
    }

    /**
     * Save validated import data
     */
    public function saveImport(Request $request)
    {
        $importData = session('import_data', []);

        if (empty($importData)) {
            return redirect()->route('rekrutasi-dosen.import.view')
                ->with('error', 'Tidak ada data untuk diimport.');
        }

        $successCount = 0;
        $failCount = 0;
        $savedData = [];

        foreach ($importData as $row) {
            if ($row['is_valid']) {
                try {
                    // Create Calon Dosen (no_registrasi auto-generated, status auto-set to "Seleksi")
                    $calonDosen = CalonDosen::create([
                        'nama' => $row['nama_calon'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'prodi_id' => $row['prodi_id'],
                        'tahun_ajar_id' => $row['tahun_ajar_id'],
                        'status_penerimaan' => 'Seleksi',
                        'jalur_lamaran' => $row['jalur_lamaran'] ?? null,
                        'h_index' => $row['h_index'] ?? null,
                    ]);

                    // Create S1 Education History (always required)
                    \App\Models\RiwayatPendidikanCalonDosen::create([
                        'calon_dosen_id' => $calonDosen->id,
                        'jenjang' => 'S1',
                        'nama_universitas' => $row['universitas_s1'],
                        'prodi_pendidikan' => $row['prodi_s1'],
                        'tanggal_lulus' => $row['tanggal_lulus_s1'],
                    ]);

                    // Create S2 Education History (if data exists)
                    if ($row['has_s2']) {
                        \App\Models\RiwayatPendidikanCalonDosen::create([
                            'calon_dosen_id' => $calonDosen->id,
                            'jenjang' => 'S2',
                            'nama_universitas' => $row['universitas_s2'],
                            'prodi_pendidikan' => $row['prodi_s2'],
                            'tanggal_lulus' => $row['tanggal_lulus_s2'],
                        ]);
                    }

                    // Create S3 Education History (if data exists)
                    if ($row['has_s3']) {
                        \App\Models\RiwayatPendidikanCalonDosen::create([
                            'calon_dosen_id' => $calonDosen->id,
                            'jenjang' => 'S3',
                            'nama_universitas' => $row['universitas_s3'],
                            'prodi_pendidikan' => $row['prodi_s3'],
                            'tanggal_lulus' => $row['tanggal_lulus_s3'],
                        ]);
                    }

                    // Store row data with no_registrasi for download
                    $row['no_registrasi'] = $calonDosen->no_registrasi;
                    $savedData[] = $row;
                    
                    $successCount++;
                } catch (\Exception $e) {
                    logger()->error('Import save error for row: ' . json_encode($row));
                    logger()->error('Error details: ' . $e->getMessage());
                    logger()->error('Stack trace: ' . $e->getTraceAsString());
                    $failCount++;
                }
            } else {
                $failCount++;
            }
        }

        // Store result in session for "Selesai" page
        session(['import_result' => [
            'success' => $successCount,
            'failed' => $failCount,
            'data' => $savedData
        ]]);

        // Clear import data
        session()->forget('import_data');

        return redirect()->route('rekrutasi-dosen.import.result')
            ->with('success', "Import selesai! {$successCount} data berhasil, {$failCount} data gagal.");
    }

    /**
     * Show import result page
     */
    public function importResult()
    {
        $result = session('import_result', []);

        return view('rekrutasi-dosen.import-result', compact('result'));
    }

    /**
     * Download import result Excel
     */
    public function downloadImportResult()
    {
        $result = session('import_result', []);

        if (empty($result['data'])) {
            return redirect()->back()->with('error', 'Tidak ada data untuk didownload.');
        }

        $filename = 'hasil-import-rekrutasi-' . date('Y-m-d-His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($result) {
            echo $this->generateResultExcel($result['data']);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate result Excel with valid data
     */
    private function generateResultExcel($data)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Styles>
  <Style ss:ID="Header">
   <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
   <Interior ss:Color="#C41E3A" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="Data">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hasil Import">
  <Table>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="100"/>
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="180"/>
   <Column ss:Width="100"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">No. Registrasi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jenis Kelamin</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tahun Ajar</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jalur Lamaran</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">H-Index</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S3</Data></Cell>
   </Row>';

        foreach ($data as $row) {
            $tglS1 = !empty($row['tanggal_lulus_s1']) ? \Carbon\Carbon::parse($row['tanggal_lulus_s1'])->format('d/m/Y') : '-';
            $tglS2 = !empty($row['tanggal_lulus_s2']) ? \Carbon\Carbon::parse($row['tanggal_lulus_s2'])->format('d/m/Y') : '-';
            $tglS3 = !empty($row['tanggal_lulus_s3']) ? \Carbon\Carbon::parse($row['tanggal_lulus_s3'])->format('d/m/Y') : '-';
            
            $xml .= '
   <Row>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['no_registrasi'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nama_calon'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['jenis_kelamin'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['tahun_ajar'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_name'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['jalur_lamaran'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['h_index'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s1'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s1'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($tglS1, ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s2'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s2'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($tglS2, ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s3'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s3'] ?? '-', ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($tglS3, ENT_XML1, 'UTF-8') . '</Data></Cell>
   </Row>';
        }

        $xml .= '
  </Table>
 </Worksheet>
</Workbook>';

        return $xml;
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
            logger()->error('Export Excel Error: ' . $e->getMessage());
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
            logger()->error('Export CSV Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Export CSV gagal: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = CalonDosen::with(['prodi', 'tahunAjar']);

        // Apply filters
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }
        if ($request->filled('jenjang')) {
            $query->whereHas('prodi', function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            });
        }
        if ($request->filled('tahun_ajar')) {
            $query->where('tahun_ajar_id', $request->tahun_ajar);
        }
        if ($request->filled('status')) {
            $query->where('status_penerimaan', $request->status);
        }

        $rekrutasi = $query->latest()->get();

        $pdf = Pdf::loadView('rekrutasi-dosen.export-pdf', compact('rekrutasi'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('rekrutasi-dosen-' . date('Y-m-d-His') . '.pdf');
    }

    /**
     * Download riwayat pendidikan file (ijazah/transkrip)
     */
    public function downloadRiwayatFile($filename)
    {
        $path = storage_path('app/public/riwayat_pendidikan/' . $filename);

        Log::info('Download file request:', [
            'filename' => $filename,
            'path' => $path,
            'exists' => file_exists($path)
        ]);

        if (!file_exists($path)) {
            // Coba cek di folder riwayat_pendidikan langsung
            $altPath = storage_path('app/riwayat_pendidikan/' . $filename);
            Log::info('Trying alternative path:', [
                'path' => $altPath,
                'exists' => file_exists($altPath)
            ]);
            
            if (file_exists($altPath)) {
                return response()->file($altPath);
            }
            
            abort(404, 'File tidak ditemukan: ' . $filename);
        }

        return response()->file($path);
    }
}
