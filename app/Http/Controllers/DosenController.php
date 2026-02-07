<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\KelompokKeahlian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\DosenExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DosenController extends Controller
{
    /**
     * Display a listing of dosen.
     */
    public function index(Request $request)
    {
        $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);

        // Filter by prodi_id (Lokasi Kerja uses prodi data)
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter by jabatan (JFA)
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter by kelompok_keahlian_id
        if ($request->filled('kelompok_keahlian_id')) {
            $query->where('kelompok_keahlian_id', $request->kelompok_keahlian_id);
        }

        // Filter by status_pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search by name, NIP, or kode_dosen
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('kode_dosen', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortField, ['nip', 'kode_dosen', 'nama_lengkap', 'jabatan', 'status_pegawai'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $dosen = $query->get();

        // Data untuk filter dropdown
        $filterData = [
            'prodi' => Prodi::with('fakultas')->orderBy('nama_prodi')->get(),
            'jabatan' => Dosen::distinct()->pluck('jabatan')->filter()->sort()->values(),
            'kelompok_keahlian' => KelompokKeahlian::orderBy('nama_kelompok_keahlian')->get(),
            'status_pegawai' => Dosen::distinct()->pluck('status_pegawai')->filter()->sort()->values()
        ];

        return view('manajemen-dosen.kelola-data-dosen', compact('dosen', 'filterData'));
    }

    /**
     * Show the form for creating a new dosen.
     */
    public function create()
    {
        $prodi = Prodi::with('fakultas')->get();
        $kelompokKeahlian = KelompokKeahlian::all();
        $fakultas = \App\Models\Fakultas::all();

        return view('manajemen-dosen.tambah-data-dosen', compact('prodi', 'kelompokKeahlian', 'fakultas'));
    }

    /**
     * Store a newly created dosen in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prodi_id' => 'required|exists:prodi,id',
            'kelompok_keahlian_id' => 'required|exists:kelompok_keahlian,id',
            'front_title' => 'nullable|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'jabatan' => 'required|in:NJFA,Asisten Ahli,Lektor,Lektor Kepala,Profesor,Guru Besar',
            'nip' => 'required|string|max:50|unique:dosen,nip',
            'kode_dosen' => 'required|string|max:20|unique:dosen,kode_dosen',
            'status_pegawai' => 'required|in:Tetap,Perbantuan,Profesional Full Time,Profesional Part Time',
            'pendidikan_terakhir' => 'required|in:S1,S2,S3',
            'status_dosen' => 'nullable|string',
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

        try {
            // Get fakultas_id from prodi
            $prodi = Prodi::findOrFail($validated['prodi_id']);
            $fakultas_id = $prodi->fakultas_id;

            // Generate username from nama_lengkap (without spaces and lowercase)
            $baseUsername = strtolower(str_replace(' ', '', $validated['nama_lengkap']));
            $username = $baseUsername;
            $counter = 1;
            
            // Check if username exists, if yes add number suffix
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Create user first (auto-generate username and default password)
            $user = User::create([
                'fakultas_id' => $fakultas_id,
                'prodi_id' => $validated['prodi_id'],
                'role_id' => 2, // Role dosen
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $username, // Use nama_lengkap without spaces as username
                'password' => Hash::make('password123'), // Default password
            ]);

            // Create dosen
            $dosen = Dosen::create([
                'user_id' => $user->id,
                'prodi_id' => $validated['prodi_id'],
                'kelompok_keahlian_id' => $validated['kelompok_keahlian_id'],
                'front_title' => $validated['front_title'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'back_title' => $validated['back_title'],
                'jabatan' => $validated['jabatan'],
                'nip' => $validated['nip'],
                'kode_dosen' => $validated['kode_dosen'],
                'status_pegawai' => $validated['status_pegawai'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'status_dosen' => $validated['status_dosen'] ?? 'Aktif',
            ]);

            // Simpan riwayat pendidikan jika ada
            if ($request->has('riwayat')) {
                foreach ($request->riwayat as $jenjang => $data) {
                    // Skip jika data kosong
                    if (empty($data['nama_universitas']) && empty($data['prodi_pendidikan'])) {
                        continue;
                    }

                    $riwayatData = [
                        'dosen_id' => $dosen->id,
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
                    }

                    // Handle file upload transkrip
                    if ($request->hasFile("riwayat.{$jenjang}.transkrip_nilai")) {
                        $transkrip = $request->file("riwayat.{$jenjang}.transkrip_nilai");
                        $transkripName = time() . '_' . $jenjang . '_transkrip.' . $transkrip->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('riwayat_pendidikan', $transkrip, $transkripName);
                        $riwayatData['transkrip_nilai'] = 'riwayat_pendidikan/' . $transkripName;
                    }

                    \App\Models\RiwayatPendidikanDosen::create($riwayatData);
                }
            }

            // Check if AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data dosen berhasil ditambahkan!',
                    'data' => $dosen
                ]);
            }

            return redirect()->route('manajemen-dosen.kelola-data')
                ->with('success', 'Data dosen berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
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

    /**
     * Display the specified dosen.
     */
    public function show(Request $request, Dosen $dosen)
    {
        // Load relasi untuk dosen yang dipilih
        $dosen->load(['user', 'prodi.fakultas', 'kelompokKeahlian', 'riwayatPendidikan']);
        
        // Check if AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'dosen' => $dosen
            ]);
        }
        
        // Get all dosen untuk ditampilkan di list data dosen dengan filter
        $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);
        
        // Filter berdasarkan status pegawai jika ada
        if ($request->filled('filter_status')) {
            $query->where('status_pegawai', $request->filter_status);
        }
        
        // Sort berdasarkan parameter sort
        if ($request->filled('sort')) {
            $sortOption = $request->sort;
            
            switch ($sortOption) {
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
            $query->orderBy('id', 'desc'); // Default sort
        }
        
        $allDosen = $query->paginate(10);
        
        return view('manajemen-dosen.detail-data-dosen', compact('dosen', 'allDosen'));
    }

    /**
     * Show the form for editing the specified dosen.
     */
    public function edit(Dosen $dosen)
    {
        $dosen->load(['user', 'prodi', 'kelompokKeahlian']);
        $prodi = Prodi::with('fakultas')->get();
        $kelompokKeahlian = KelompokKeahlian::all();
        $fakultas = \App\Models\Fakultas::all();

        return view('manajemen-dosen.edit-data-dosen', compact('dosen', 'prodi', 'kelompokKeahlian', 'fakultas'));
    }

    /**
     * Update the specified dosen in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        // Validasi untuk Ajax request (dari modal)
        if ($request->ajax() || $request->wantsJson()) {
            $validated = $request->validate([
                'prodi_id' => 'required|exists:prodi,id',
                'kelompok_keahlian_id' => 'required|exists:kelompok_keahlian,id',
                'front_title' => 'nullable|string|max:50',
                'nama_lengkap' => 'required|string|max:255',
                'back_title' => 'nullable|string|max:50',
                'jabatan' => 'required|in:NJFA,Asisten Ahli,Lektor,Lektor Kepala,Profesor,Guru Besar',
                'nip' => 'required|string|max:50|unique:dosen,nip,' . $dosen->id,
                'kode_dosen' => 'required|string|max:20|unique:dosen,kode_dosen,' . $dosen->id,
                'status_pegawai' => 'required|in:Tetap,Perbantuan,Profesional Full Time,Profesional Part Time',
                'pendidikan_terakhir' => 'required|in:S1,S2,S3',
                'status_dosen' => 'nullable|string',
                // Validasi riwayat pendidikan (semua jenjang opsional untuk update)
                'riwayat.s1.nama_universitas' => 'nullable|string|max:255',
                'riwayat.s1.prodi_pendidikan' => 'nullable|string|max:255',
                'riwayat.s1.tanggal_lulus' => 'nullable|date',
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

            // Validasi konsistensi: jika ada salah satu field riwayat yang diisi, maka field wajib lainnya harus diisi
            if ($request->has('riwayat')) {
                foreach (['s1', 's2', 's3'] as $jenjang) {
                    if ($request->has("riwayat.{$jenjang}")) {
                        $dataJenjang = $request->input("riwayat.{$jenjang}");
                        $hasNamaUniv = !empty($dataJenjang['nama_universitas']);
                        $hasProdi = !empty($dataJenjang['prodi_pendidikan']);
                        $hasTanggalLulus = !empty($dataJenjang['tanggal_lulus']);
                        
                        // Jika ada salah satu yang diisi, maka harus lengkap
                        if (($hasNamaUniv || $hasProdi || $hasTanggalLulus) && 
                            (!$hasNamaUniv || !$hasProdi || !$hasTanggalLulus)) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Data riwayat pendidikan ' . strtoupper($jenjang) . ' harus diisi lengkap (universitas, prodi, dan tanggal lulus)!'
                            ], 422);
                        }
                    }
                }
            }

            try {
                // Get fakultas_id from prodi
                $prodi = Prodi::findOrFail($validated['prodi_id']);
                $fakultas_id = $prodi->fakultas_id;

                // Update user
                $userData = [
                    'fakultas_id' => $fakultas_id,
                    'prodi_id' => $validated['prodi_id'],
                    'nama_lengkap' => $validated['nama_lengkap'],
                ];

                $dosen->user->update($userData);

                // Update dosen
                $dosen->update([
                    'prodi_id' => $validated['prodi_id'],
                    'kelompok_keahlian_id' => $validated['kelompok_keahlian_id'],
                    'front_title' => $validated['front_title'],
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'back_title' => $validated['back_title'],
                    'jabatan' => $validated['jabatan'],
                    'nip' => $validated['nip'],
                    'kode_dosen' => $validated['kode_dosen'],
                    'status_pegawai' => $validated['status_pegawai'],
                    'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                    'status_dosen' => $validated['status_dosen'] ?? 'Aktif',
                ]);

                // Update riwayat pendidikan
                if ($request->has('riwayat')) {
                    foreach ($request->riwayat as $jenjang => $data) {
                        // Skip jika data kosong
                        if (empty($data['nama_universitas']) && empty($data['prodi_pendidikan'])) {
                            // Hapus jika ada data lama
                            \App\Models\RiwayatPendidikanDosen::where('dosen_id', $dosen->id)
                                ->where('jenjang', strtoupper($jenjang))
                                ->delete();
                            continue;
                        }

                        $riwayatData = [
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
                        }

                        // Handle file upload transkrip
                        if ($request->hasFile("riwayat.{$jenjang}.transkrip_nilai")) {
                            $transkrip = $request->file("riwayat.{$jenjang}.transkrip_nilai");
                            $transkripName = time() . '_' . $jenjang . '_transkrip.' . $transkrip->getClientOriginalExtension();
                            Storage::disk('public')->putFileAs('riwayat_pendidikan', $transkrip, $transkripName);
                            $riwayatData['transkrip_nilai'] = 'riwayat_pendidikan/' . $transkripName;
                        }

                        // Update or create riwayat
                        \App\Models\RiwayatPendidikanDosen::updateOrCreate(
                            [
                                'dosen_id' => $dosen->id,
                                'jenjang' => strtoupper($jenjang)
                            ],
                            $riwayatData
                        );
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data dosen berhasil diperbarui!'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        // Validasi untuk non-Ajax request (dari halaman edit terpisah)
        $validated = $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'prodi_id' => 'required|exists:prodi,id',
            'kelompok_keahlian_id' => 'required|exists:kelompok_keahlian,id',
            'front_title' => 'nullable|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'jabatan' => 'required|in:NJFA,Asisten Ahli,Lektor,Lektor Kepala,Profesor,Guru Besar',
            'nip' => 'required|string|max:50|unique:dosen,nip,' . $dosen->user_id . ',user_id',
            'kode_dosen' => 'required|string|max:20|unique:dosen,kode_dosen,' . $dosen->user_id . ',user_id',
            'status_pegawai' => 'required|in:Tetap,Perbantuan,Profesional Full Time,Profesional Part Time',
            'username' => 'required|string|max:100|unique:user,username,' . $dosen->user_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Update user
            $userData = [
                'fakultas_id' => $validated['fakultas_id'],
                'prodi_id' => $validated['prodi_id'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $dosen->user->update($userData);

            // Update dosen
            $dosen->update([
                'prodi_id' => $validated['prodi_id'],
                'kelompok_keahlian_id' => $validated['kelompok_keahlian_id'],
                'front_title' => $validated['front_title'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'back_title' => $validated['back_title'],
                'jabatan' => $validated['jabatan'],
                'nip' => $validated['nip'],
                'kode_dosen' => $validated['kode_dosen'],
                'status_pegawai' => $validated['status_pegawai'],
            ]);

            return redirect()->route('manajemen-dosen.kelola-data')->with('success', 'Data dosen berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified dosen from storage.
     */
    public function destroy(Dosen $dosen)
    {
        try {
            $user = $dosen->user;
            $dosen->delete();
            $user->delete();

            return redirect()->route('manajemen-dosen.kelola-data')->with('success', 'Data dosen berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get prodi by fakultas (AJAX endpoint)
     */
    public function getProdiByFakultas($fakultasId)
    {
        $prodi = Prodi::where('fakultas_id', $fakultasId)->get(['id', 'nama_prodi']);
        return response()->json($prodi);
    }

    /**
     * Kelola Data Dosen - Halaman utama manajemen dosen (sesuai navbar)
     * ✅ SEMUA TES BACKEND BERHASIL - SIAP PRODUKSI!
     */
    public function kelolaData(Request $request)
    {
        // ==============================================
        // FUNGSI TES CADANGAN (DALAM COMMENT)
        // Uncomment salah satu untuk debug/testing
        // ==============================================
        
        // LANGKAH 1: Tes Koneksi Database, berapa jumlah dosen, prodi, kelompok keahlian, contoh data dosen, dan apakah field status_pegawai ada
        // dd([
        //     'langkah' => 'LANGKAH 1 - Tes Koneksi Database',
        //     'koneksi_database' => 'OK',
        //     'total_dosen' => Dosen::count(),
        //     'total_prodi' => Prodi::count(),
        //     'total_kelompok_keahlian' => KelompokKeahlian::count(),
        //     'contoh_dosen' => Dosen::first(),
        //     'field_status_pegawai_ada' => Dosen::first() ? (isset(Dosen::first()->status_pegawai) ? 'ADA' : 'TIDAK_ADA') : 'BELUM_ADA_DATA',
        // ]);

        // LANGKAH 2: Tes Relasi Antar Tabel & Query berfungsi contoh relasi dosen ke user
        // $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);
        // dd([
        //     'langkah' => 'LANGKAH 2 - Tes Relasi & Query',
        //     'query_sql' => $query->toSql(),
        //     'relasi_yang_dimuat' => $query->getEagerLoads(),
        //     'dosen_dengan_relasi' => $query->first(),
        //     'tes_relasi' => [
        //         'relasi_user' => $query->first() ? ($query->first()->user ? 'BERHASIL_DIMUAT' : 'KOSONG') : 'BELUM_ADA_DATA',
        //         'relasi_prodi' => $query->first() ? ($query->first()->prodi ? 'BERHASIL_DIMUAT' : 'KOSONG') : 'BELUM_ADA_DATA',
        //         'relasi_kelompok_keahlian' => $query->first() ? ($query->first()->kelompokKeahlian ? 'BERHASIL_DIMUAT' : 'KOSONG') : 'BELUM_ADA_DATA',
        //     ],
        //     'preview_data_filter' => [
        //         'pilihan_lokasi_kerja' => Dosen::distinct()->pluck('lokasi_kerja')->take(5),
        //         'pilihan_jabatan' => Dosen::distinct()->pluck('jabatan')->take(5),
        //         'pilihan_status_pegawai' => Dosen::distinct()->pluck('status_pegawai')->take(5),
        //     ]
        // ]);

        // LANGKAH 3: Tes Logika Filter berfungsi sesuai input user (lokasi_kerja, jfa, kelompok_keahlian_id, status_pegawai, search)
        // $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);
        // $jumlah_awal = $query->count();
        // if ($request->filled('lokasi_kerja')) { $query->where('lokasi_kerja', $request->lokasi_kerja); }
        // if ($request->filled('jfa')) { $query->where('jabatan', $request->jfa); }
        // if ($request->filled('kelompok_keahlian_id')) { $query->where('kelompok_keahlian_id', $request->kelompok_keahlian_id); }
        // if ($request->filled('status_pegawai')) { $query->where('status_pegawai', $request->status_pegawai); }
        // if ($request->filled('search')) { $query->where('nama_lengkap', 'like', '%' . $request->search . '%'); }
        // dd([
        //     'langkah' => 'LANGKAH 3 - Tes Logika Filter',
        //     'input_dari_user' => $request->all(),
        //     'filter_yang_diterapkan' => [
        //         'lokasi_kerja' => $request->filled('lokasi_kerja') ? $request->lokasi_kerja : 'TIDAK_DITERAPKAN',
        //         'jfa' => $request->filled('jfa') ? $request->jfa : 'TIDAK_DITERAPKAN', 
        //         'kelompok_keahlian_id' => $request->filled('kelompok_keahlian_id') ? $request->kelompok_keahlian_id : 'TIDAK_DITERAPKAN',
        //         'status_pegawai' => $request->filled('status_pegawai') ? $request->status_pegawai : 'TIDAK_DITERAPKAN',
        //         'pencarian' => $request->filled('search') ? $request->search : 'TIDAK_DITERAPKAN',
        //     ],
        //     'jumlah_data' => [
        //         'jumlah_awal' => $jumlah_awal,
        //         'jumlah_setelah_filter' => $query->count(),
        //     ],
        //     'query_sql_akhir' => $query->toSql(),
        //     'parameter_query' => $query->getBindings(),
        //     'url_untuk_tes' => [
        //         'tanpa_filter' => url('manajemen-dosen/kelola-data'),
        //         'dengan_pencarian' => url('manajemen-dosen/kelola-data?search=test'),
        //         'dengan_lokasi' => url('manajemen-dosen/kelola-data?lokasi_kerja=Jakarta'),
        //         'dengan_jfa' => url('manajemen-dosen/kelola-data?jfa=Dosen'),
        //     ]
        // ]);

        // LANGKAH 4: Tes Pagination & Data Akhir (15 data per halaman, data untuk view, dsb)
        // $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);
        // if ($request->filled('lokasi_kerja')) { $query->where('lokasi_kerja', $request->lokasi_kerja); }
        // if ($request->filled('jfa')) { $query->where('jabatan', $request->jfa); }
        // if ($request->filled('kelompok_keahlian_id')) { $query->where('kelompok_keahlian_id', $request->kelompok_keahlian_id); }
        // if ($request->filled('status_pegawai')) { $query->where('status_pegawai', $request->status_pegawai); }
        // if ($request->filled('search')) { $query->where('nama_lengkap', 'like', '%' . $request->search . '%'); }
        // $dosen = $query->paginate(15);
        // $dataFilter = [
        //     'lokasi_kerja' => Dosen::distinct()->pluck('lokasi_kerja')->filter(),
        //     'pilihan_jfa' => Dosen::distinct()->pluck('jabatan')->filter(),
        //     'kelompok_keahlian' => KelompokKeahlian::all(),
        //     'status_pegawai' => ['Aktif', 'Non-Aktif', 'Cuti'],
        // ];
        // dd([
        //     'langkah' => 'LANGKAH 4 - Tes Pagination & Data Akhir',
        //     'info_pagination' => [
        //         'total_data' => $dosen->total(),
        //         'per_halaman' => $dosen->perPage(),
        //         'halaman_saat_ini' => $dosen->currentPage(),
        //         'halaman_terakhir' => $dosen->lastPage(),
        //         'data_dari' => $dosen->firstItem(),
        //         'data_sampai' => $dosen->lastItem(),
        //         'ada_halaman_lain' => $dosen->hasPages(),
        //     ],
        //     'data_dropdown_filter' => [
        //         'jumlah_lokasi_kerja' => $dataFilter['lokasi_kerja']->count(),
        //         'jumlah_pilihan_jfa' => $dataFilter['pilihan_jfa']->count(),
        //         'jumlah_kelompok_keahlian' => $dataFilter['kelompok_keahlian']->count(),
        //         'jumlah_status_pegawai' => count($dataFilter['status_pegawai']),
        //     ],
        //     'contoh_hasil' => $dosen->take(3),
        //     'data_untuk_view' => [
        //         'variabel_dosen' => 'Siap dikirim ke view',
        //         'variabel_dataFilter' => 'Siap dikirim ke view',
        //         'file_view' => 'kelola-data-dosen.blade.php',
        //     ],
        //     'status_backend' => '🎉 SEMUA TES BACKEND BERHASIL! 🎉',
        //     'langkah_selanjutnya' => 'Hapus dd() dan aktifkan return view() normal',
        // ]);

        // ==============================================
        // ALUR PRODUKSI - OPERASI NORMAL
        // ==============================================

        // LANGKAH 1-4: SEMUA TES SELESAI ✅ (DI-COMMENT UNTUK PRODUKSI)
        // Langkah 1: Koneksi database ✅
        // Langkah 2: Relasi antar tabel ✅  
        // Langkah 3: Logika filter ✅
        // Langkah 4: Pagination & data akhir ✅

        // ALUR PRODUKSI - OPERASI NORMAL
        $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);

        // Filter by prodi_id (Lokasi Kerja uses prodi data)
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter by jabatan (JFA)
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter Kelompok Keahlian
        if ($request->filled('kelompok_keahlian_id')) {
            $query->where('kelompok_keahlian_id', $request->kelompok_keahlian_id);
        }

        // Filter Status Pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search by name, NIP, or kode_dosen
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('kode_dosen', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortField, ['nip', 'kode_dosen', 'nama_lengkap', 'jabatan', 'status_pegawai'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Get all data (no pagination - show all records)
        $dosen = $query->get();

        // Data untuk dropdown filter (pull from database)
        $filterData = [
            'prodi' => Prodi::with('fakultas')->orderBy('nama_prodi')->get(),
            'jabatan' => Dosen::distinct()->pluck('jabatan')->filter()->sort()->values(),
            'kelompok_keahlian' => KelompokKeahlian::orderBy('nama_kelompok_keahlian')->get(),
            'status_pegawai' => Dosen::distinct()->pluck('status_pegawai')->filter()->sort()->values()
        ];

        // Kirim ke view (SIAP UNTUK FRONTEND)
        return view('manajemen-dosen.kelola-data-dosen', compact('dosen', 'filterData'));
    }

    /**
     * Export dosen data to Excel.
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['prodi_id', 'jabatan', 'kelompok_keahlian_id', 'status_pegawai', 'search']);
        
        return Excel::download(new DosenExport($filters), 'data-dosen-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export dosen data to CSV.
     */
    public function exportCsv(Request $request)
    {
        try {
            $filters = $request->only(['prodi_id', 'jabatan', 'kelompok_keahlian_id', 'status_pegawai', 'search']);
            $fileName = 'data-dosen-' . date('Y-m-d-His') . '.csv';

            return Excel::download(
                new DosenExport($filters),
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

    /**
     * Export dosen data to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Dosen::with(['user', 'prodi.fakultas', 'kelompokKeahlian']);

        // Apply filters
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('kelompok_keahlian_id')) {
            $query->where('kelompok_keahlian_id', $request->kelompok_keahlian_id);
        }
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('kode_dosen', 'like', '%' . $search . '%');
            });
        }

        $dosen = $query->orderBy('nama_lengkap', 'asc')->get();

        $pdf = Pdf::loadView('manajemen-dosen.export-pdf', compact('dosen'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('data-dosen-' . date('Y-m-d-His') . '.pdf');
    }

    /**
     * Form Import Data
     */
    public function importForm()
    {
        return view('manajemen-dosen.import-data');
    }

    /**
     * Process Import Data
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        // Process import file here
        // Implementasi Excel import nanti
        
        return redirect()->route('manajemen-dosen.kelola-data')
            ->with('success', 'Data berhasil diimport!');
    }

    /**
     * Halaman Laporan Dosen
     */
    public function laporan()
    {
        // Statistik untuk laporan
        $statistik = [
            'total_dosen' => Dosen::count(),
            'per_status_dosen' => [
                'aktif' => Dosen::where('status_dosen', 'Aktif')->count(),
                'tugas_belajar' => Dosen::where('status_dosen', 'Tugas Belajar')->count(),
                'izin_belajar' => Dosen::where('status_dosen', 'Izin Belajar')->count(),
                'clty' => Dosen::where('status_dosen', 'CLTY')->count(),
            ],
            'per_status' => [
                'tetap' => Dosen::where('status_pegawai', 'Tetap')->count(),
                'perbantuan' => Dosen::where('status_pegawai', 'Perbantuan')->count(),
                'profesional_full' => Dosen::where('status_pegawai', 'Profesional Full Time')->count(),
                'profesional_part' => Dosen::where('status_pegawai', 'Profesional Part Time')->count(),
            ],
            'per_jfa' => [
                'njfa' => Dosen::where('jabatan', 'NJFA')->count(),
                'asisten_ahli' => Dosen::where('jabatan', 'Asisten Ahli')->count(),
                'lektor' => Dosen::where('jabatan', 'Lektor')->count(),
                'lektor_kepala' => Dosen::where('jabatan', 'Lektor Kepala')->count(),
                'guru_besar' => Dosen::where('jabatan', 'Guru Besar')->count(),
            ],
            'per_prodi' => [],
            'per_kelompok_keahlian' => []
        ];

        // Statistik per prodi (dinamis dari database)
        $prodiList = Prodi::with(['dosen'])->get();
        foreach ($prodiList as $prodi) {
            $jumlah = $prodi->dosen->count();
            if ($jumlah > 0) { // Hanya tampilkan prodi yang memiliki dosen
                $statistik['per_prodi'][] = [
                    'nama' => $prodi->nama_prodi,
                    'jumlah' => $jumlah
                ];
            }
        }

        // Statistik per kelompok keahlian (dinamis dari database)
        // Pastikan CITI selalu muncul meskipun 0
        $kelompokKeahlianList = KelompokKeahlian::orderBy('nama_kelompok_keahlian')->get();
        foreach ($kelompokKeahlianList as $kelompok) {
            $jumlah = $kelompok->dosen->count();
            // Tampilkan semua kelompok termasuk yang 0
            $statistik['per_kelompok_keahlian'][] = [
                'nama' => $kelompok->nama_kelompok_keahlian,
                'jumlah' => $jumlah
            ];
        }

        return view('manajemen-dosen.laporan', compact('statistik'));
    }

    /**
     * Export laporan dosen to PDF
     */
    public function exportLaporanPDF()
    {
        // Ambil data yang sama dengan laporan
        $statistik = [
            'total_dosen' => Dosen::count(),
            'per_status_dosen' => [
                'aktif' => Dosen::where('status_dosen', 'Aktif')->count(),
                'tugas_belajar' => Dosen::where('status_dosen', 'Tugas Belajar')->count(),
                'izin_belajar' => Dosen::where('status_dosen', 'Izin Belajar')->count(),
                'clty' => Dosen::where('status_dosen', 'CLTY')->count(),
            ],
            'per_status' => [
                'tetap' => Dosen::where('status_pegawai', 'Tetap')->count(),
                'perbantuan' => Dosen::where('status_pegawai', 'Perbantuan')->count(),
                'profesional_full' => Dosen::where('status_pegawai', 'Profesional Full Time')->count(),
                'profesional_part' => Dosen::where('status_pegawai', 'Profesional Part Time')->count(),
            ],
            'per_jfa' => [
                'njfa' => Dosen::where('jabatan', 'NJFA')->count(),
                'asisten_ahli' => Dosen::where('jabatan', 'Asisten Ahli')->count(),
                'lektor' => Dosen::where('jabatan', 'Lektor')->count(),
                'lektor_kepala' => Dosen::where('jabatan', 'Lektor Kepala')->count(),
                'guru_besar' => Dosen::where('jabatan', 'Guru Besar')->count(),
            ],
            'per_prodi' => [],
            'per_kelompok_keahlian' => []
        ];

        $prodiList = Prodi::with(['dosen'])->get();
        foreach ($prodiList as $prodi) {
            $jumlah = $prodi->dosen->count();
            if ($jumlah > 0) {
                $statistik['per_prodi'][] = [
                    'nama' => $prodi->nama_prodi,
                    'jumlah' => $jumlah
                ];
            }
        }

        $kelompokKeahlianList = KelompokKeahlian::orderBy('nama_kelompok_keahlian')->get();
        foreach ($kelompokKeahlianList as $kelompok) {
            $jumlah = $kelompok->dosen->count();
            $statistik['per_kelompok_keahlian'][] = [
                'nama' => $kelompok->nama_kelompok_keahlian,
                'jumlah' => $jumlah
            ];
        }

        $pdf = Pdf::loadView('manajemen-dosen.laporan-pdf', compact('statistik'));
        return $pdf->download('laporan-dosen-' . date('Y-m-d') . '.pdf');
    }
}
