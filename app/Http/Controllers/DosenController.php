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
                $q->where('dosen.nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('dosen.nip', 'like', '%' . $search . '%')
                  ->orWhere('dosen.kode_dosen', 'like', '%' . $search . '%')
                  ->orWhereHas('prodi', function($q) use ($search) {
                      $q->where('nama_prodi', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('kelompokKeahlian', function($q) use ($search) {
                      $q->where('nama_kelompok_keahlian', 'like', '%' . $search . '%');
                  });
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

        $dosen = $query->paginate(10)->withQueryString();

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
        $this->authorize('kelola-data-dosen.create');
        
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
        $this->authorize('kelola-data-dosen.create');
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
            \App\Models\Notification::sendToAll('Data Baru', "Dosen baru telah ditambahkan: {$dosen->nama_lengkap}", route('manajemen-dosen.show', $dosen->id));

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
        $this->authorize('kelola-data-dosen.detail');
        
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
        $this->authorize('kelola-data-dosen.edit');
        
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
        $this->authorize('kelola-data-dosen.edit');
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

                \App\Models\Notification::sendToAll('Perubahan Data', "Data dosen {$dosen->nama_lengkap} telah diperbarui", route('manajemen-dosen.show', $dosen->id));

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

            \App\Models\Notification::sendToAll('Perubahan Data', "Data dosen {$dosen->nama_lengkap} telah diperbarui", route('manajemen-dosen.show', $dosen->id));

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
        $this->authorize('kelola-data-dosen.delete');
        
        try {
            $namaDosen = $dosen->nama_lengkap;
            $user = $dosen->user;
            $dosen->delete();
            $user->delete();

            \App\Models\Notification::sendToAll('Perubahan Data', "Data dosen {$namaDosen} telah dihapus");

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
        // Authorization check
        $this->authorize('kelola-data-dosen.view');
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

        // Paginate with 10 items per page
        $dosen = $query->paginate(10)->withQueryString();

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
    /**
     * Show import view
     */
    public function importView(Request $request)
    {
        $this->authorize('import-data-dosen.view');
        
        // Get current step from request
        $step = $request->get('step');
        
        // If step is not specified, determine based on session
        if (!$step) {
            if (session()->has('import_result_dosen')) {
                // If result exists, redirect to result page
                return redirect()->route('manajemen-dosen.import.result');
            } elseif (session()->has('import_data_dosen')) {
                // If import data exists, go to step 2
                $step = 2;
            } else {
                // Default to step 1
                $step = 1;
            }
        }
        
        // If navigating to step 1, clear import data but keep import_result if exists
        if ($step == 1 && $request->has('step')) {
            session()->forget(['import_data_dosen', 'show_import_dosen']);
        }
        
        // Clear all import sessions if reset is requested
        if ($request->get('reset') == '1') {
            session()->forget(['import_data_dosen', 'show_import_dosen', 'import_result_dosen', 'file_uploaded_dosen']);
        }

        return view('manajemen-dosen.import-data-dosen');
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $this->authorize('import-data-dosen.view');
        
        $fakultasList = \App\Models\Fakultas::pluck('nama_fakultas')->toArray();
        $prodiList = \App\Models\Prodi::pluck('nama_prodi')->toArray();
        $kelompokKeahlianList = \App\Models\KelompokKeahlian::pluck('nama_kelompok_keahlian')->toArray();

        $filename = 'template-import-dosen-' . date('Y-m-d-His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($fakultasList, $prodiList, $kelompokKeahlianList) {
            echo $this->generateTemplateXML($fakultasList, $prodiList, $kelompokKeahlianList);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate template XML for Excel
     */
    private function generateTemplateXML($fakultasList, $prodiList, $kelompokKeahlianList)
    {
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
 <Worksheet ss:Name="Template Data Dosen">
  <Table>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
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
    <Cell ss:StyleID="Header"><Data ss:Type="String">Gelar Depan</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama Lengkap</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Gelar Belakang</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">NIP</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Kode Dosen</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Kelompok Keahlian</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">JFA</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Status Pegawai</Data></Cell>
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
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Gelar depan (opsional): Dr., Prof., dll</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama lengkap dosen (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Gelar belakang (opsional): S.Kom, M.Kom, Ph.D</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nomor Induk Pegawai (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Kode dosen (WAJIB, harus unik): DSN001, DSN002</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama program studi (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">Nama kelompok keahlian (WAJIB)</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">WAJIB: NJFA / Asisten Ahli / Lektor / Lektor Kepala / Profesor</Data></Cell>
    <Cell ss:StyleID="Instruction"><Data ss:Type="String">WAJIB: Tetap / Perbantuan / Profesional Full Time / Profesional Part Time</Data></Cell>
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
    <Cell ss:StyleID="Example"><Data ss:Type="String">Dr.</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">John Doe</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">M.Kom, Ph.D</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">1234567890</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">DSN001</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">' . ($prodiList[0] ?? 'Sistem Informasi') . '</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">' . ($kelompokKeahlianList[0] ?? 'Artificial Intelligence') . '</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Lektor</Data></Cell>
    <Cell ss:StyleID="Example"><Data ss:Type="String">Tetap</Data></Cell>
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
        $this->authorize('import-data-dosen.view');
        
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
                'import_data_dosen' => $validatedData, 
                'show_import_dosen' => true,
                'file_uploaded_dosen' => true
            ]);

            $validCount = collect($validatedData)->where('is_valid', true)->count();
            $totalCount = count($validatedData);

            return redirect()->route('manajemen-dosen.import.view', ['step' => 2])
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
                if (count($row) >= 16) {
                    $hasData = false;
                    foreach (array_slice($row, 0, 5) as $cell) {
                        if (!empty(trim($cell))) {
                            $hasData = true;
                            break;
                        }
                    }
                    
                    if ($hasData) {
                        $data[] = [
                            'front_title' => trim($row[0] ?? ''),
                            'nama_lengkap' => trim($row[1]),
                            'back_title' => trim($row[2] ?? ''),
                            'nip' => trim($row[3]),
                            'kode_dosen' => trim($row[4]),
                            'prodi' => trim($row[5]),
                            'kelompok_keahlian' => trim($row[6]),
                            'jabatan' => trim($row[7]),
                            'status_pegawai' => trim($row[8]),
                            'universitas_s1' => trim($row[9]),
                            'prodi_s1' => trim($row[10]),
                            'tanggal_lulus_s1' => trim($row[11]),
                            'universitas_s2' => trim($row[12] ?? ''),
                            'prodi_s2' => trim($row[13] ?? ''),
                            'tanggal_lulus_s2' => trim($row[14] ?? ''),
                            'universitas_s3' => trim($row[15] ?? ''),
                            'prodi_s3' => trim($row[16] ?? ''),
                            'tanggal_lulus_s3' => trim($row[17] ?? ''),
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
                    if (count($row) >= 16) {
                        $hasData = false;
                        foreach (array_slice($row, 0, 5) as $cell) {
                            if (!empty(trim($cell))) {
                                $hasData = true;
                                break;
                            }
                        }
                        
                        if ($hasData) {
                            $data[] = [
                                'front_title' => trim($row[0] ?? ''),
                                'nama_lengkap' => trim($row[1] ?? ''),
                                'back_title' => trim($row[2] ?? ''),
                                'nip' => trim($row[3] ?? ''),
                                'kode_dosen' => trim($row[4] ?? ''),
                                'prodi' => trim($row[5] ?? ''),
                                'kelompok_keahlian' => trim($row[6] ?? ''),
                                'jabatan' => trim($row[7] ?? ''),
                                'status_pegawai' => trim($row[8] ?? ''),
                                'universitas_s1' => trim($row[9] ?? ''),
                                'prodi_s1' => trim($row[10] ?? ''),
                                'tanggal_lulus_s1' => trim($row[11] ?? ''),
                                'universitas_s2' => trim($row[12] ?? ''),
                                'prodi_s2' => trim($row[13] ?? ''),
                                'tanggal_lulus_s2' => trim($row[14] ?? ''),
                                'universitas_s3' => trim($row[15] ?? ''),
                                'prodi_s3' => trim($row[16] ?? ''),
                                'tanggal_lulus_s3' => trim($row[17] ?? ''),
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

            // Validate Nama Lengkap (required)
            if (empty($row['nama_lengkap'])) {
                $errors[] = 'Nama lengkap kosong';
            }

            // Validate NIP (required)
            if (empty($row['nip'])) {
                $errors[] = 'NIP kosong';
            } else {
                // Check NIP uniqueness
                if (\App\Models\Dosen::where('nip', $row['nip'])->exists()) {
                    $errors[] = 'NIP sudah terdaftar';
                }
            }

            // Validate Kode Dosen (required)
            if (empty($row['kode_dosen'])) {
                $errors[] = 'Kode dosen kosong';
            } else {
                // Check Kode Dosen uniqueness
                if (\App\Models\Dosen::where('kode_dosen', $row['kode_dosen'])->exists()) {
                    $errors[] = 'Kode dosen sudah terdaftar';
                }
            }

            // Set Fakultas otomatis ke "Fakultas Informatika"
            $fakultas = \App\Models\Fakultas::where('nama_fakultas', 'Fakultas Informatika')->first();
            if (!$fakultas) {
                $errors[] = 'Fakultas Informatika tidak ditemukan di database';
            }

            // Validate Prodi (required)
            $prodi = null;
            if (empty($row['prodi'])) {
                $errors[] = 'Program studi kosong';
            } else {
                $prodi = \App\Models\Prodi::where('nama_prodi', $row['prodi'])->first();
                if (!$prodi) {
                    $errors[] = 'Program studi tidak ditemukan';
                }
            }

            // Validate Kelompok Keahlian (required)
            $kelompokKeahlian = null;
            if (empty($row['kelompok_keahlian'])) {
                $errors[] = 'Kelompok keahlian kosong';
            } else {
                $kelompokKeahlian = \App\Models\KelompokKeahlian::where('nama_kelompok_keahlian', $row['kelompok_keahlian'])->first();
                if (!$kelompokKeahlian) {
                    $errors[] = 'Kelompok keahlian tidak ditemukan';
                }
            }

            // Validate JFA (required)
            $validJFA = ['NJFA', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor'];
            if (empty($row['jabatan'])) {
                $errors[] = 'JFA kosong';
            } elseif (!in_array($row['jabatan'], $validJFA)) {
                $errors[] = 'JFA tidak valid';
            }

            // Validate Status Pegawai (required)
            $validStatusPegawai = ['Tetap', 'Perbantuan', 'Profesional Full Time', 'Profesional Part Time'];
            if (empty($row['status_pegawai'])) {
                $errors[] = 'Status pegawai kosong';
            } elseif (!in_array($row['status_pegawai'], $validStatusPegawai)) {
                $errors[] = 'Status pegawai tidak valid';
            }

            // Validate Riwayat Pendidikan S1 (required)
            if (empty($row['universitas_s1'])) {
                $errors[] = 'Universitas S1 kosong';
            }
            if (empty($row['prodi_s1'])) {
                $errors[] = 'Program Studi S1 kosong';
            }
            if (empty($row['tanggal_lulus_s1'])) {
                $errors[] = 'Tanggal Lulus S1 kosong';
            } else {
                // Validate date format
                $date = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s1']);
                if (!$date || $date->format('d/m/Y') !== $row['tanggal_lulus_s1']) {
                    $errors[] = 'Format Tanggal Lulus S1 tidak valid (harus dd/mm/yyyy)';
                }
            }

            // Validate Riwayat Pendidikan S2 (optional, but if filled all fields must be complete)
            $hasS2 = !empty($row['universitas_s2']) || !empty($row['prodi_s2']) || !empty($row['tanggal_lulus_s2']);
            if ($hasS2) {
                if (empty($row['universitas_s2'])) {
                    $errors[] = 'Universitas S2 harus diisi jika mengisi data S2';
                }
                if (empty($row['prodi_s2'])) {
                    $errors[] = 'Program Studi S2 harus diisi jika mengisi data S2';
                }
                if (empty($row['tanggal_lulus_s2'])) {
                    $errors[] = 'Tanggal Lulus S2 harus diisi jika mengisi data S2';
                } else {
                    $date = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s2']);
                    if (!$date || $date->format('d/m/Y') !== $row['tanggal_lulus_s2']) {
                        $errors[] = 'Format Tanggal Lulus S2 tidak valid (harus dd/mm/yyyy)';
                    }
                }
            }

            // Validate Riwayat Pendidikan S3 (optional, but if filled all fields must be complete)
            $hasS3 = !empty($row['universitas_s3']) || !empty($row['prodi_s3']) || !empty($row['tanggal_lulus_s3']);
            if ($hasS3) {
                if (empty($row['universitas_s3'])) {
                    $errors[] = 'Universitas S3 harus diisi jika mengisi data S3';
                }
                if (empty($row['prodi_s3'])) {
                    $errors[] = 'Program Studi S3 harus diisi jika mengisi data S3';
                }
                if (empty($row['tanggal_lulus_s3'])) {
                    $errors[] = 'Tanggal Lulus S3 harus diisi jika mengisi data S3';
                } else {
                    $date = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s3']);
                    if (!$date || $date->format('d/m/Y') !== $row['tanggal_lulus_s3']) {
                        $errors[] = 'Format Tanggal Lulus S3 tidak valid (harus dd/mm/yyyy)';
                    }
                }
            }

            // Store validated data
            $validated[] = [
                'front_title' => $row['front_title'],
                'nama_lengkap' => $row['nama_lengkap'],
                'back_title' => $row['back_title'],
                'nip' => $row['nip'],
                'kode_dosen' => $row['kode_dosen'],
                'fakultas_id' => $fakultas ? $fakultas->id : null,
                'fakultas_name' => $fakultas ? $fakultas->nama_fakultas : 'Fakultas Informatika',
                'prodi' => $row['prodi'],
                'prodi_id' => $prodi ? $prodi->id : null,
                'prodi_name' => $prodi ? $prodi->nama_prodi : $row['prodi'],
                'kelompok_keahlian' => $row['kelompok_keahlian'],
                'kelompok_keahlian_id' => $kelompokKeahlian ? $kelompokKeahlian->id : null,
                'kelompok_keahlian_name' => $kelompokKeahlian ? $kelompokKeahlian->nama_kelompok_keahlian : $row['kelompok_keahlian'],
                'jabatan' => $row['jabatan'],
                'status_pegawai' => $row['status_pegawai'],
                'status_dosen' => 'Aktif',
                'universitas_s1' => $row['universitas_s1'],
                'prodi_s1' => $row['prodi_s1'],
                'tanggal_lulus_s1' => $row['tanggal_lulus_s1'],
                'universitas_s2' => $row['universitas_s2'],
                'prodi_s2' => $row['prodi_s2'],
                'tanggal_lulus_s2' => $row['tanggal_lulus_s2'],
                'universitas_s3' => $row['universitas_s3'],
                'prodi_s3' => $row['prodi_s3'],
                'tanggal_lulus_s3' => $row['tanggal_lulus_s3'],
                'is_valid' => empty($errors),
                'errors' => $errors
            ];
        }

        return $validated;
    }

    /**
     * Save validated import data to database
     */
    public function saveImport(Request $request)
    {
        $this->authorize('import-data-dosen.view');
        
        $importData = session('import_data_dosen', []);

        if (empty($importData)) {
            return redirect()->route('manajemen-dosen.import.view', ['step' => 1])
                ->with('error', 'Tidak ada data untuk disimpan.');
        }

        $successCount = 0;
        $failCount = 0;
        $savedData = [];

        foreach ($importData as $row) {
            if ($row['is_valid']) {
                try {
                    \DB::beginTransaction();

                    // Auto-generate username from nama_lengkap
                    $username = strtolower(str_replace(' ', '', $row['nama_lengkap']));
                    
                    // Check if username exists, add number suffix if needed
                    $originalUsername = $username;
                    $counter = 1;
                    while (\App\Models\User::where('username', $username)->exists()) {
                        $username = $originalUsername . $counter;
                        $counter++;
                    }

                    // Get or create dosen role
                    $role = \Spatie\Permission\Models\Role::firstOrCreate(
                        ['name' => 'dosen', 'guard_name' => 'web']
                    );

                    // Create User with role_id
                    $user = \App\Models\User::create([
                        'nama_lengkap' => $row['nama_lengkap'],
                        'username' => $username,
                        'password' => bcrypt('password123'), // Default password
                        'role_id' => $role->id,
                    ]);

                    // Assign dosen role via Spatie
                    $user->assignRole($role);

                    // Create Dosen
                    $dosen = \App\Models\Dosen::create([
                        'user_id' => $user->id,
                        'front_title' => $row['front_title'],
                        'nama_lengkap' => $row['nama_lengkap'],
                        'back_title' => $row['back_title'],
                        'nip' => $row['nip'],
                        'kode_dosen' => $row['kode_dosen'],
                        'prodi_id' => $row['prodi_id'],
                        'kelompok_keahlian_id' => $row['kelompok_keahlian_id'],
                        'jabatan' => $row['jabatan'],
                        'status_pegawai' => $row['status_pegawai'],
                        'status_dosen' => $row['status_dosen'],
                    ]);

                    // Save Riwayat Pendidikan S1 (required)
                    if (!empty($row['universitas_s1']) && !empty($row['tanggal_lulus_s1'])) {
                        $tanggalS1 = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s1']);
                        if ($tanggalS1) {
                            \App\Models\RiwayatPendidikanDosen::create([
                                'dosen_id' => $dosen->id,
                                'jenjang' => 'S1',
                                'nama_universitas' => $row['universitas_s1'],
                                'prodi_pendidikan' => $row['prodi_s1'],
                                'tanggal_lulus' => $tanggalS1->format('Y-m-d'),
                            ]);
                        }
                    }

                    // Save Riwayat Pendidikan S2 (optional)
                    if (!empty($row['universitas_s2']) && !empty($row['prodi_s2']) && !empty($row['tanggal_lulus_s2'])) {
                        $tanggalS2 = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s2']);
                        if ($tanggalS2) {
                            \App\Models\RiwayatPendidikanDosen::create([
                                'dosen_id' => $dosen->id,
                                'jenjang' => 'S2',
                                'nama_universitas' => $row['universitas_s2'],
                                'prodi_pendidikan' => $row['prodi_s2'],
                                'tanggal_lulus' => $tanggalS2->format('Y-m-d'),
                            ]);
                        }
                    }

                    // Save Riwayat Pendidikan S3 (optional)
                    if (!empty($row['universitas_s3']) && !empty($row['prodi_s3']) && !empty($row['tanggal_lulus_s3'])) {
                        $tanggalS3 = \DateTime::createFromFormat('d/m/Y', $row['tanggal_lulus_s3']);
                        if ($tanggalS3) {
                            \App\Models\RiwayatPendidikanDosen::create([
                                'dosen_id' => $dosen->id,
                                'jenjang' => 'S3',
                                'nama_universitas' => $row['universitas_s3'],
                                'prodi_pendidikan' => $row['prodi_s3'],
                                'tanggal_lulus' => $tanggalS3->format('Y-m-d'),
                            ]);
                        }
                    }

                    \DB::commit();

                    // Store row data for download
                    $savedData[] = $row;
                    
                    $successCount++;
                } catch (\Exception $e) {
                    \DB::rollBack();
                    \Log::error('Import save error for row: ' . $row['nama_lengkap']);
                    \Log::error('Error message: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    $failCount++;
                }
            } else {
                $failCount++;
            }
        }

        // Store result in session for "Selesai" page
        session(['import_result_dosen' => [
            'success' => $successCount,
            'failed' => $failCount,
            'data' => $savedData
        ]]);

        // Clear import data
        session()->forget('import_data_dosen');

        return redirect()->route('manajemen-dosen.import.result')
            ->with('success', "Import selesai! {$successCount} data berhasil, {$failCount} data gagal.");
    }

    /**
     * Show import result page
     */
    public function importResult()
    {
        $this->authorize('import-data-dosen.view');
        
        $result = session('import_result_dosen', []);

        return view('manajemen-dosen.import-result-dosen', compact('result'));
    }

    /**
     * Download import result Excel
     */
    public function downloadImportResult()
    {
        $this->authorize('import-data-dosen.view');
        
        $result = session('import_result_dosen', []);

        if (empty($result['data'])) {
            return redirect()->back()->with('error', 'Tidak ada data untuk didownload.');
        }

        $filename = 'hasil-import-dosen-' . date('Y-m-d-His') . '.xls';

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
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="200"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
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
    <Cell ss:StyleID="Header"><Data ss:Type="String">Gelar Depan</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama Lengkap</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Gelar Belakang</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">NIP</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Kode Dosen</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Kelompok Keahlian</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">JFA</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Status Pegawai</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S1</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S2</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Universitas S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi S3</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal Lulus S3</Data></Cell>
   </Row>';

        foreach ($data as $row) {
            $xml .= '
   <Row ss:Height="22">
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['front_title'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nama_lengkap']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['back_title'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nip']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['kode_dosen']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_name']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['kelompok_keahlian_name']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['jabatan']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['status_pegawai']) . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s1'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s1'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['tanggal_lulus_s1'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s2'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s2'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['tanggal_lulus_s2'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['universitas_s3'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_s3'] ?? '') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['tanggal_lulus_s3'] ?? '') . '</Data></Cell>
   </Row>';
        }

        $xml .= '
  </Table>
 </Worksheet>
</Workbook>';

        return $xml;
    }

    /**
     * Halaman Laporan Dosen
     */
    public function laporan()
    {
        $this->authorize('laporan-data-dosen.view');
        
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
        $this->authorize('laporan-data-dosen.view');
        
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
