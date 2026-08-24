<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\TenagaPendukungAkademikController;
use App\Http\Controllers\RekrutasiDosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KompetisiController;
use App\Http\Controllers\TahunAjarController;
use App\Http\Controllers\KelompokKeahlianController;
use App\Http\Controllers\SuratDosenController;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TenagaPendukungAkademik;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

// ============================
// Auth Routes
// ============================
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::post('/switch-role', function (Illuminate\Http\Request $request) {
    $role = $request->input('role');
    $user = Auth::user();

    if ($user && $user->roles->contains('name', $role)) {
        session()->put('active_role', $role);
        return redirect()->route('dashboard')->with('success', 'Role berhasil diganti ke ' . $role);
    }

    return redirect()->back()->with('error', 'Akses ditolak.');
})->name('switch-role')->middleware('auth');

// ============================
// Public Dashboard 
// ============================
// Bagian Dashboard
Route::get('/dashboard', function () {
    $totalDosen = \App\Models\Dosen::count();
    $dosenAktif = \App\Models\Dosen::where('status_dosen', 'Aktif')->count();
    $dosenTugasBelajar = \App\Models\Dosen::where('status_dosen', 'Tugas Belajar')->count();
    $dosenIzinBelajar = \App\Models\Dosen::where('status_dosen', 'Izin Belajar')->count();

    $pendidikanDosen = \App\Models\Dosen::select('pendidikan_terakhir', DB::raw('count(*) as count'))
        ->groupBy('pendidikan_terakhir')
        ->pluck('count', 'pendidikan_terakhir')
        ->toArray();

    $jadDosen = \App\Models\Dosen::select('jabatan', DB::raw('count(*) as count'))
        ->groupBy('jabatan')
        ->pluck('count', 'jabatan')
        ->toArray();

    $nisbah = \App\Models\Prodi::withCount([
    'dosen',
    'mahasiswa'
])
    ->get()
    ->map(function ($prodi) {

        $hasilNisbah = $prodi->dosen_count > 0
            ? round($prodi->mahasiswa_count / $prodi->dosen_count, 2)
            : null;

        return [
            'nama_prodi' => $prodi->nama_prodi,
            'jumlah_dosen' => $prodi->dosen_count,
            'jumlah_mahasiswa' => $prodi->mahasiswa_count,
            'batas_nisbah' => $prodi->batas_nisbah,
            'hasil_nisbah' => $hasilNisbah,

            'status' => $prodi->dosen_count == 0
                ? 'Belum Ada Dosen'
                : ($hasilNisbah > $prodi->batas_nisbah
                    ? 'Melebihi'
                    : 'Sesuai'),
        ];
    });


    $jumlahDosenProdi = \App\Models\Prodi::withCount('dosen')
        ->pluck('dosen_count', 'nama_prodi')
        ->toArray();

    return view('dashboard', compact('totalDosen', 'dosenAktif', 'dosenTugasBelajar', 'dosenIzinBelajar', 'pendidikanDosen', 'jadDosen', 'nisbah', 'jumlahDosenProdi'));
})->name('dashboard');

// Landing Page
Route::get('/', function () {
    $totalDosen = \App\Models\Dosen::count();
    $dosenAktif = \App\Models\Dosen::where('status_dosen', 'Aktif')->count();
    $dosenTugasBelajar = \App\Models\Dosen::where('status_dosen', 'Tugas Belajar')->count();
    $dosenIzinBelajar = \App\Models\Dosen::where('status_dosen', 'Izin Belajar')->count();

    $pendidikanDosen = \App\Models\Dosen::select('pendidikan_terakhir', DB::raw('count(*) as count'))
        ->groupBy('pendidikan_terakhir')
        ->pluck('count', 'pendidikan_terakhir')
        ->toArray();

    $jadDosen = \App\Models\Dosen::select('jabatan', DB::raw('count(*) as count'))
        ->groupBy('jabatan')
        ->pluck('count', 'jabatan')
        ->toArray();

    $nisbah = \App\Models\Prodi::withCount([
    'dosen',
    'mahasiswa'
])
    ->get()
    ->map(function ($prodi) {

        $hasilNisbah = $prodi->dosen_count > 0
            ? round($prodi->mahasiswa_count / $prodi->dosen_count, 2)
            : null;

        return [
            'nama_prodi' => $prodi->nama_prodi,
            'jumlah_dosen' => $prodi->dosen_count,
            'jumlah_mahasiswa' => $prodi->mahasiswa_count,
            'batas_nisbah' => $prodi->batas_nisbah,
            'hasil_nisbah' => $hasilNisbah,

            'status' => $prodi->dosen_count == 0
                ? 'Belum Ada Dosen'
                : ($hasilNisbah > $prodi->batas_nisbah
                    ? 'Melebihi'
                    : 'Sesuai'),
        ];
    });

    $jumlahDosenProdi = \App\Models\Prodi::withCount('dosen')
        ->pluck('dosen_count', 'nama_prodi')
        ->toArray();

    return view('landingpage', compact('totalDosen', 'dosenAktif', 'dosenTugasBelajar', 'dosenIzinBelajar', 'pendidikanDosen', 'jadDosen', 'nisbah', 'jumlahDosenProdi'));
})->name('guest');
Route::get('/guest-dosen', function () {
    $dosenProdi = \App\Models\Dosen::join('prodi', 'dosen.prodi_id', '=', 'prodi.id')
        ->select('prodi.nama_prodi', DB::raw('count(*) as count'))
        ->groupBy('prodi.nama_prodi')
        ->pluck('count', 'prodi.nama_prodi')
        ->toArray();

    $dosenKK = \App\Models\Dosen::join('kelompok_keahlian', 'dosen.kelompok_keahlian_id', '=', 'kelompok_keahlian.id')
        ->select('kelompok_keahlian.nama_kelompok_keahlian', DB::raw('count(*) as count'))
        ->groupBy('kelompok_keahlian.nama_kelompok_keahlian')
        ->pluck('count', 'kelompok_keahlian.nama_kelompok_keahlian')
        ->toArray();

    $pendDosen = \App\Models\Dosen::select('pendidikan_terakhir', DB::raw('count(*) as count'))
        ->groupBy('pendidikan_terakhir')
        ->pluck('count', 'pendidikan_terakhir')
        ->toArray();
    $pendDosen['ONGOING'] = \App\Models\Dosen::whereNotNull('status_studi_lanjut')->count();

    $jfaDosen = \App\Models\Dosen::select('jabatan', DB::raw('count(*) as count'))
        ->groupBy('jabatan')
        ->pluck('count', 'jabatan')
        ->toArray();

    $statusDosen = \App\Models\Dosen::select('status_pegawai', DB::raw('count(*) as count'))
        ->groupBy('status_pegawai')
        ->pluck('count', 'status_pegawai')
        ->toArray();

    return view('guest-dosen', compact('dosenProdi', 'dosenKK', 'pendDosen', 'jfaDosen', 'statusDosen'));
})->name('guest-dosen');

Route::get('/guest-tpa', [TenagaPendukungAkademikController::class, 'guestDashboard'])
    ->name('guest-tpa');

Route::get('/guest-kompetisi', function () {
    $kompetisiTahun = \App\Models\Kompetisi::selectRaw('YEAR(tanggal_kompetisi) as year, tingkat_kompetisi, count(*) as count')
        ->groupBy('year', 'tingkat_kompetisi')
        ->get();

    $juaraTahun = \Illuminate\Support\Facades\DB::table('mahasiswa_kompetisi')
        ->join('kompetisi', 'mahasiswa_kompetisi.kompetisi_id', '=', 'kompetisi.id')
        ->selectRaw('YEAR(kompetisi.tanggal_kompetisi) as year, mahasiswa_kompetisi.juara, count(*) as count')
        ->whereNotNull('mahasiswa_kompetisi.juara')
        ->groupBy('year', 'mahasiswa_kompetisi.juara')
        ->get();

    $kompetisiProdi = \Illuminate\Support\Facades\DB::table('mahasiswa_kompetisi')
        ->join('mahasiswa', 'mahasiswa_kompetisi.mahasiswa_id', '=', 'mahasiswa.id')
        ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
        ->select('prodi.nama_prodi', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('prodi.nama_prodi')
        ->get();

    $kompetisiKategori = \App\Models\Kompetisi::select('jenis', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('jenis')
        ->get();

    $mahasiswaProdi = \App\Models\Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
        ->select('prodi.nama_prodi', DB::raw('count(*) as count'))
        ->groupBy('prodi.nama_prodi')
        ->pluck('count', 'prodi.nama_prodi')
        ->toArray();

    $mahasiswaStatus = \App\Models\Mahasiswa::select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    $mahasiswaAngkatan = \App\Models\Mahasiswa::select(DB::raw("CONCAT('20', SUBSTRING(nim, 1, 2)) as angkatan"), DB::raw('count(*) as count'))
        ->groupBy('angkatan')
        ->pluck('count', 'angkatan')
        ->toArray();

    $juaraDoughnut = DB::table('mahasiswa_kompetisi')
        ->select('juara', DB::raw('count(*) as count'))
        ->whereIn('juara', ['Juara 1', 'Juara 2', 'Juara 3'])
        ->groupBy('juara')
        ->pluck('count', 'juara')
        ->toArray();

    $jenisDoughnut = \App\Models\Kompetisi::select('jenis', DB::raw('count(*) as count'))
        ->groupBy('jenis')
        ->pluck('count', 'jenis')
        ->toArray();

    return view('guest-kompetisi', compact(
        'kompetisiTahun',
        'juaraTahun',
        'kompetisiProdi',
        'kompetisiKategori',
        'mahasiswaProdi',
        'mahasiswaStatus',
        'mahasiswaAngkatan',
        'juaraDoughnut',
        'jenisDoughnut'
    ));
})->name('guest-kompetisi');

Route::get('/dashboard-dosen', function () {
    $studiLanjut = \App\Models\Dosen::with('prodi')
        ->whereNotNull('status_studi_lanjut')
        ->get(['id', 'nama_lengkap', 'prodi_id', 'jabatan', 'status_studi_lanjut', 'lokasi_kampus_studi', 'tahun_mulai_studi', 'batas_studi']);

    $nisbah = \App\Models\Prodi::withCount([
    'dosen',
    'mahasiswa'
])
    ->get()
    ->map(function ($prodi) {

        $rasio = $prodi->dosen_count > 0
            ? round($prodi->mahasiswa_count / $prodi->dosen_count, 2)
            : null;

        return [
            'nama_prodi' => $prodi->nama_prodi,
            'dosen' => $prodi->dosen_count,
            'mahasiswa' => $prodi->mahasiswa_count,
            'batas_nisbah' => $prodi->batas_nisbah,
            'rasio' => $rasio,

            'over_limit' => $rasio !== null
                && $rasio > $prodi->batas_nisbah,
        ];
    });
     

    $totalDosen = \App\Models\Dosen::count();

    $totalStudiLanjut = \App\Models\Dosen::whereNotNull('status_studi_lanjut')
        ->count();

    $s1 = \App\Models\Dosen::where('pendidikan_terakhir', 'S1')
        ->count();

    $s2 = \App\Models\Dosen::where('pendidikan_terakhir', 'S2')
        ->count();

    $s3 = \App\Models\Dosen::where('pendidikan_terakhir', 'S3')
        ->count();

    $prodiOverNisbah = collect($nisbah)
        ->where('over_limit', true)
        ->count();

    $pendidikanPerProdi = collect([
        'Informatika',
        'Rekayasa Perangkat Lunak',
        'Data Sains',
        'Teknologi Informasi'
    ])->map(function ($namaProdi) {

        $dosen = \App\Models\Dosen::whereHas('prodi', function ($q) use ($namaProdi) {
            $q->where('nama_prodi', 'like', '%' . $namaProdi . '%');
        })->get();

        return [
            'nama_prodi' => $namaProdi,
            's1' => $dosen->where('pendidikan_terakhir', 'S1')->count(),
            's2' => $dosen->where('pendidikan_terakhir', 'S2')->count(),
            's3' => $dosen->where('pendidikan_terakhir', 'S3')->count(),
            'total' => $dosen->count(),
        ];
    });

    $dosenProdi = \App\Models\Dosen::join('prodi', 'dosen.prodi_id', '=', 'prodi.id')
        ->select('prodi.nama_prodi', DB::raw('count(*) as count'))
        ->groupBy('prodi.nama_prodi')
        ->pluck('count', 'prodi.nama_prodi')
        ->toArray();

    $dosenKK = \App\Models\Dosen::join('kelompok_keahlian', 'dosen.kelompok_keahlian_id', '=', 'kelompok_keahlian.id')
        ->select('kelompok_keahlian.nama_kelompok_keahlian', DB::raw('count(*) as count'))
        ->groupBy('kelompok_keahlian.nama_kelompok_keahlian')
        ->pluck('count', 'kelompok_keahlian.nama_kelompok_keahlian')
        ->toArray();

    $pendDosen = \App\Models\Dosen::select('pendidikan_terakhir', DB::raw('count(*) as count'))
        ->groupBy('pendidikan_terakhir')
        ->pluck('count', 'pendidikan_terakhir')
        ->toArray();
    $pendDosen['ONGOING'] = $totalStudiLanjut;

    $jfaDosen = \App\Models\Dosen::select('jabatan', DB::raw('count(*) as count'))
        ->groupBy('jabatan')
        ->pluck('count', 'jabatan')
        ->toArray();

    $statusDosen = \App\Models\Dosen::select('status_pegawai', DB::raw('count(*) as count'))
        ->groupBy('status_pegawai')
        ->pluck('count', 'status_pegawai')
        ->toArray();

    return view('dashboard-dosen', compact(
        'studiLanjut',
        'nisbah',
        'totalDosen',
        'totalStudiLanjut',
        's1',
        's2',
        's3',
        'prodiOverNisbah',
        'pendidikanPerProdi',
        'dosenProdi',
        'dosenKK',
        'pendDosen',
        'jfaDosen',
        'statusDosen'
    ));
})->name('dashboard-dosen');

Route::get('/dashboard-tpa', [TenagaPendukungAkademikController::class, 'dashboard'])->name('dashboard-tpa');

// ============================
// 🧪 DEBUG ROUTES FOR FRONTEND (DEVELOPMENT ONLY)
// ============================
Route::prefix('debug')->group(function () {

    // Debug Test: Kelola Data Dosen (Bypass Auth untuk Testing)
    Route::get('/kelola-data-dosen', [DosenController::class, 'kelolaData'])
        ->name('debug.kelola-data-dosen');

    // Debug 1: Data Dosen Lengkap untuk Frontend
    Route::get('/data-dosen', function () {
        $dosen = App\Models\Dosen::with(['prodi.fakultas', 'kelompokKeahlian', 'user'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen siap untuk frontend',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'summary' => [
                'total_dosen' => $dosen->count(),
                'fakultas' => $dosen->pluck('prodi.fakultas.nama_fakultas')->unique()->values(),
                'prodi' => $dosen->pluck('prodi.nama_prodi')->unique()->values(),
                'lokasi_kerja' => $dosen->pluck('lokasi_kerja')->unique()->values(),
                'jabatan' => $dosen->pluck('jabatan')->unique()->values(),
                'status_pegawai' => $dosen->pluck('status_pegawai')->unique()->values(),
            ],
            'data_dosen' => $dosen->map(function ($d) {
                return [
                    'id' => $d->id,
                    'nama_lengkap' => $d->front_title . ' ' . $d->nama_lengkap . ', ' . $d->back_title,
                    'nama_tanpa_gelar' => $d->nama_lengkap,
                    'front_title' => $d->front_title,
                    'back_title' => $d->back_title,
                    'nip' => $d->nip,
                    'kode_dosen' => $d->kode_dosen,
                    'jabatan' => $d->jabatan,
                    'lokasi_kerja' => $d->lokasi_kerja,
                    'status_pegawai' => $d->status_pegawai,
                    'prodi' => [
                        'id' => $d->prodi->id,
                        'nama' => $d->prodi->nama_prodi,
                        'fakultas' => $d->prodi->fakultas->nama_fakultas
                    ],
                    'kelompok_keahlian' => [
                        'id' => $d->kelompokKeahlian->id,
                        'nama' => $d->kelompokKeahlian->nama_kelompok_keahlian
                    ],
                    'user' => [
                        'id' => $d->user->id,
                        'username' => $d->user->username,
                        'nama_lengkap' => $d->user->nama_lengkap
                    ]
                ];
            }),
            'statistik_dashboard' => [
                'status_pegawai' => [
                    'aktif' => $dosen->where('status_pegawai', 'Aktif')->count(),
                    'non_aktif' => $dosen->where('status_pegawai', 'Non-Aktif')->count(),
                    'cuti' => $dosen->where('status_pegawai', 'Cuti')->count(),
                ],
                'per_jabatan' => $dosen->groupBy('jabatan')->map(function ($group) {
                    return $group->count();
                }),
                'per_lokasi' => $dosen->groupBy('lokasi_kerja')->map(function ($group) {
                    return $group->count();
                }),
                'per_prodi' => $dosen->groupBy('prodi.nama_prodi')->map(function ($group) {
                    return $group->count();
                }),
                'per_kelompok_keahlian' => $dosen->groupBy('kelompokKeahlian.nama_kelompok_keahlian')->map(function ($group) {
                    return $group->count();
                })
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    });

    // Debug 2: Filter Options untuk Dropdown
    Route::get('/filter-options', function () {
        return response()->json([
            'success' => true,
            'message' => 'Data filter options untuk dropdown frontend',
            'filter_options' => [
                'lokasi_kerja' => App\Models\Dosen::distinct()->pluck('lokasi_kerja')->filter()->values(),
                'jabatan' => App\Models\Dosen::distinct()->pluck('jabatan')->filter()->values(),
                'status_pegawai' => ['Aktif', 'Non-Aktif', 'Cuti'],
                'prodi' => App\Models\Prodi::with('fakultas')->get()->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_prodi,
                        'fakultas' => $p->fakultas->nama_fakultas
                    ];
                }),
                'kelompok_keahlian' => App\Models\KelompokKeahlian::all()->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'nama' => $k->nama_kelompok_keahlian
                    ];
                })
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    });

    // Debug 3: Test Controller Method
    Route::get('/test-controller', [DosenController::class, 'kelolaData']);

    // Debug 4: Database Connection Test
    Route::get('/db-test', function () {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Database connection OK',
                'tables_count' => [
                    'fakultas' => App\Models\Fakultas::count(),
                    'prodi' => App\Models\Prodi::count(),
                    'kelompok_keahlian' => App\Models\KelompokKeahlian::count(),
                    'dosen' => App\Models\Dosen::count(),
                    'users' => App\Models\User::count(),
                    'roles' => Spatie\Permission\Models\Role::count(),
                    'permissions' => Spatie\Permission\Models\Permission::count(),
                ],
                'sample_data' => [
                    'fakultas' => App\Models\Fakultas::first(),
                    'prodi' => App\Models\Prodi::first(),
                    'dosen' => App\Models\Dosen::first(),
                ]
            ], 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    });
});

// ============================
// Protected Routes (harus login)
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-read/{id}', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [\App\Http\Controllers\NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

    // Dashboard - with permission check and smart redirect


    // Dashboard Dosen

    // ============================
    // 🆕 MASTER DATA ROUTES (Fakultas, Prodi, Kompetisi)
    // ============================
    // Menggunakan Route::resource agar otomatis ada index, create, store, edit, update, destroy
    Route::prefix('master-data')->middleware(['auth'])->group(function () {

        // Fakultas - view permission for all roles, edit permission for Super Admin only
        Route::middleware('can:master-data-fakultas.view')->group(function () {
            Route::get('fakultas', [FakultasController::class, 'index'])->name('fakultas.index');
            Route::get('fakultas/export-excel', [FakultasController::class, 'exportExcel'])->name('fakultas.export-excel');
            Route::get('fakultas/export-pdf', [FakultasController::class, 'exportPdf'])->name('fakultas.export-pdf');

            Route::middleware(['can:master-data-fakultas.edit', 'check.crud:master'])->group(function () {
                Route::get('fakultas/create', [FakultasController::class, 'create'])->name('fakultas.create');
                Route::post('fakultas', [FakultasController::class, 'store'])->name('fakultas.store');
                Route::get('fakultas/{fakultas}/edit', [FakultasController::class, 'edit'])->name('fakultas.edit');
                Route::put('fakultas/{fakultas}', [FakultasController::class, 'update'])->name('fakultas.update');
                Route::delete('fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');
            });
        });

        // Prodi - view permission for all roles, create/edit/delete for Super Admin only
        Route::middleware('can:master-data-prodi.view')->group(function () {
            Route::get('prodi', [ProdiController::class, 'index'])->name('prodi.index');
            Route::get('prodi/export-excel', [ProdiController::class, 'exportExcel'])->name('prodi.export-excel');
            Route::get('prodi/export-pdf', [ProdiController::class, 'exportPdf'])->name('prodi.export-pdf');

            Route::middleware(['can:master-data-prodi.create', 'check.crud:master'])->group(function () {
                Route::get('prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
                Route::post('prodi', [ProdiController::class, 'store'])->name('prodi.store');
            });

            Route::middleware(['can:master-data-prodi.edit', 'check.crud:master'])->group(function () {
                Route::get('prodi/{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
                Route::put('prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
            });

            Route::middleware(['can:master-data-prodi.delete', 'check.crud:master'])->group(function () {
                Route::delete('prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
            });
        });

        // Kompetisi - view permission for all roles, create/edit/delete for Super Admin only
        Route::middleware('can:master-data-kompetisi.view')->group(function () {
            Route::get('kompetisi', [KompetisiController::class, 'index'])->name('kompetisi.index');
            Route::get('kompetisi/export-excel', [KompetisiController::class, 'exportExcel'])->name('kompetisi.export-excel');
            Route::get('kompetisi/export-pdf', [KompetisiController::class, 'exportPdf'])->name('kompetisi.export-pdf');
            Route::get('kompetisi/{kompetisi}', [KompetisiController::class, 'show'])->name('kompetisi.show');

            Route::middleware(['can:master-data-kompetisi.create', 'check.crud:master'])->group(function () {
                Route::get('kompetisi/create', [KompetisiController::class, 'create'])->name('kompetisi.create');
                Route::post('kompetisi', [KompetisiController::class, 'store'])->name('kompetisi.store');
            });

            Route::middleware(['can:master-data-kompetisi.edit', 'check.crud:master'])->group(function () {
                Route::get('kompetisi/{kompetisi}/edit', [KompetisiController::class, 'edit'])->name('kompetisi.edit');
                Route::put('kompetisi/{kompetisi}', [KompetisiController::class, 'update'])->name('kompetisi.update');
            });

            Route::middleware(['can:master-data-kompetisi.delete', 'check.crud:master'])->group(function () {
                Route::delete('kompetisi/{kompetisi}', [KompetisiController::class, 'destroy'])->name('kompetisi.destroy');
            });
        });

        // Tahun Ajar
        Route::middleware('can:master-data-tahun-ajar.view')->group(function () {
            Route::get('tahun-ajar', [TahunAjarController::class, 'index'])->name('tahun-ajar.index');
            Route::get('tahun-ajar/export-excel', [TahunAjarController::class, 'exportExcel'])->name('tahun-ajar.export-excel');
            Route::get('tahun-ajar/export-pdf', [TahunAjarController::class, 'exportPdf'])->name('tahun-ajar.export-pdf');

            Route::middleware(['can:master-data-tahun-ajar.create', 'check.crud:master'])->group(function () {
                Route::get('tahun-ajar/create', [TahunAjarController::class, 'create'])->name('tahun-ajar.create');
                Route::post('tahun-ajar', [TahunAjarController::class, 'store'])->name('tahun-ajar.store');
            });

            Route::middleware(['can:master-data-tahun-ajar.edit', 'check.crud:master'])->group(function () {
                Route::get('tahun-ajar/{tahun_ajar}/edit', [TahunAjarController::class, 'edit'])->name('tahun-ajar.edit');
                Route::put('tahun-ajar/{tahun_ajar}', [TahunAjarController::class, 'update'])->name('tahun-ajar.update');
            });

            Route::middleware(['can:master-data-tahun-ajar.delete', 'check.crud:master'])->group(function () {
                Route::delete('tahun-ajar/{tahun_ajar}', [TahunAjarController::class, 'destroy'])->name('tahun-ajar.destroy');
            });
        });

        // Kelompok Keahlian
        Route::middleware('can:master-data-kelompok-keahlian.view')->group(function () {
            Route::get('kelompok-keahlian', [KelompokKeahlianController::class, 'index'])->name('kelompok-keahlian.index');
            Route::get('kelompok-keahlian/export-excel', [KelompokKeahlianController::class, 'exportExcel'])->name('kelompok-keahlian.export-excel');
            Route::get('kelompok-keahlian/export-pdf', [KelompokKeahlianController::class, 'exportPdf'])->name('kelompok-keahlian.export-pdf');

            Route::middleware(['can:master-data-kelompok-keahlian.create', 'check.crud:master'])->group(function () {
                Route::get('kelompok-keahlian/create', [KelompokKeahlianController::class, 'create'])->name('kelompok-keahlian.create');
                Route::post('kelompok-keahlian', [KelompokKeahlianController::class, 'store'])->name('kelompok-keahlian.store');
            });

            Route::middleware(['can:master-data-kelompok-keahlian.edit', 'check.crud:master'])->group(function () {
                Route::get('kelompok-keahlian/{kelompok_keahlian}/edit', [KelompokKeahlianController::class, 'edit'])->name('kelompok-keahlian.edit');
                Route::put('kelompok-keahlian/{kelompok_keahlian}', [KelompokKeahlianController::class, 'update'])->name('kelompok-keahlian.update');
            });

            Route::middleware(['can:master-data-kelompok-keahlian.delete', 'check.crud:master'])->group(function () {
                Route::delete('kelompok-keahlian/{kelompok_keahlian}', [KelompokKeahlianController::class, 'destroy'])->name('kelompok-keahlian.destroy');
            });
        });
    });
    // ============================
    // Manajemen Dosen Routes
    // ============================
    Route::prefix('manajemen-dosen')->name('manajemen-dosen.')->middleware('can:kelola-data-dosen.view')->group(function () {
        // Kelola Data Routes (View permission required)
        Route::get('/kelola-data', [DosenController::class, 'kelolaData'])->name('kelola-data');
        Route::get('/export-excel', [DosenController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-csv', [DosenController::class, 'exportCsv'])->name('export-csv');
        Route::get('/export-pdf', [DosenController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export', [DosenController::class, 'exportExcel'])->name('export'); // Backward compatibility

        // Import Routes (Protected - Super Admin only)
        Route::middleware(['can:import-data-dosen.view', 'check.crud:dosen'])->group(function () {
            Route::get('/import', [DosenController::class, 'importView'])->name('import.view');
            Route::get('/import/template', [DosenController::class, 'downloadTemplate'])->name('import.template');
            Route::post('/import/upload', [DosenController::class, 'uploadImport'])->name('import.upload');
            Route::post('/import/save', [DosenController::class, 'saveImport'])->name('import.save');
            Route::get('/import/result', [DosenController::class, 'importResult'])->name('import.result');
            Route::get('/import/download-result', [DosenController::class, 'downloadImportResult'])->name('import.download-result');
        });

        // Laporan Routes (All roles can access)
        Route::middleware('can:laporan-data-dosen.view')->group(function () {
            Route::get('/laporan', [DosenController::class, 'laporan'])->name('laporan');
            Route::get('/laporan/export-pdf', [DosenController::class, 'exportLaporanPDF'])->name('laporan.export-pdf');
        });

        // Surat Tugas & SK Dosen Routes
        Route::prefix('surat')->name('surat.')->group(function () {
            Route::get('/dashboard', [SuratDosenController::class, 'dashboard'])->name('dashboard');
            Route::get('/', [SuratDosenController::class, 'index'])->name('index');
            Route::get('/create', [SuratDosenController::class, 'create'])->name('create')->middleware(['can:kelola-data-dosen.create', 'check.crud:surat']);
            Route::post('/', [SuratDosenController::class, 'store'])->name('store')->middleware(['can:kelola-data-dosen.create', 'check.crud:surat']);
            Route::get('/export-excel', [SuratDosenController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [SuratDosenController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{id}', [SuratDosenController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuratDosenController::class, 'edit'])->name('edit')->middleware(['can:kelola-data-dosen.edit', 'check.crud:surat']);
            Route::put('/{id}', [SuratDosenController::class, 'update'])->name('update')->middleware(['can:kelola-data-dosen.edit', 'check.crud:surat']);
            Route::delete('/{id}', [SuratDosenController::class, 'destroy'])->name('destroy')->middleware(['can:kelola-data-dosen.delete', 'check.crud:surat']);
            Route::get('/{id}/download', [SuratDosenController::class, 'download'])->name('download');
        });

        // CRUD Routes with specific permissions
        Route::get('/create', [DosenController::class, 'create'])->name('create')->middleware(['can:kelola-data-dosen.create', 'check.crud:dosen']);
        Route::post('/store', [DosenController::class, 'store'])->name('store')->middleware(['can:kelola-data-dosen.create', 'check.crud:dosen']);
        Route::get('/{dosen}', [DosenController::class, 'show'])->name('show')->middleware('can:kelola-data-dosen.detail');
        Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('edit')->middleware(['can:kelola-data-dosen.edit', 'check.crud:dosen']);
        Route::put('/{dosen}', [DosenController::class, 'update'])->name('update')->middleware(['can:kelola-data-dosen.edit', 'check.crud:dosen']);
        Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('destroy')->middleware(['can:kelola-data-dosen.delete', 'check.crud:dosen']);
    });

    // ============================
    // Manajemen TPA Routes
    // ============================
    Route::prefix('manajemen-tpa')->name('manajemen-tpa.')->middleware('can:kelola-data-tpa.view')->group(function () {
        // Kelola Data & Laporan
        Route::get('/kelola-data', [TenagaPendukungAkademikController::class, 'kelolaData'])->name('kelola-data');
        Route::get('/export-excel', [TenagaPendukungAkademikController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [TenagaPendukungAkademikController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/laporan', [TenagaPendukungAkademikController::class, 'laporan'])->name('laporan');


        Route::get('/download-template', [TenagaPendukungAkademikController::class, 'downloadTemplate'])->name('download-template');

        // Import Features (Protected - Super Admin only)
        Route::middleware(['can:import-data-tpa.view', 'check.crud:tpa'])->group(function () {
            Route::get('/import-data', [TenagaPendukungAkademikController::class, 'importForm'])->name('import-data');
            Route::post('/import-process', [TenagaPendukungAkademikController::class, 'importProcess'])->name('import-process');
            Route::post('/import-store', [TenagaPendukungAkademikController::class, 'storeImport'])->name('import.store');
        });

        // CRUD Routes with specific permissions
        Route::get('/create', [TenagaPendukungAkademikController::class, 'create'])->name('create')->middleware(['can:kelola-data-tpa.create', 'check.crud:tpa']);
        Route::post('/store', [TenagaPendukungAkademikController::class, 'store'])->name('store')->middleware(['can:kelola-data-tpa.create', 'check.crud:tpa']);

        // Letakkan route dengan parameter {tpa} di paling bawah agar tidak bentrok dengan route statis
        Route::get('/{tpa}', [TenagaPendukungAkademikController::class, 'show'])->name('show')->middleware('can:kelola-data-tpa.detail');
        Route::get('/{tpa}/edit', [TenagaPendukungAkademikController::class, 'edit'])->name('edit')->middleware(['can:kelola-data-tpa.edit', 'check.crud:tpa']);
        Route::put('/{tpa}', [TenagaPendukungAkademikController::class, 'update'])->name('update')->middleware(['can:kelola-data-tpa.edit', 'check.crud:tpa']);
        Route::delete('/{tpa}', [TenagaPendukungAkademikController::class, 'destroy'])->name('destroy')->middleware(['can:kelola-data-tpa.delete', 'check.crud:tpa']);
    });
    // ============================
    // Manajemen Mahasiswa Routes
    // ============================

    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('can:kelola-data-mahasiswa.view')->group(function () {

        // --- FITUR IMPORT (Pola Multi-Step) - Super Admin Only ---
        Route::middleware(['can:import-data-mahasiswa.view', 'check.crud:mahasiswa'])->group(function () {
            // Halaman utama import & Step 1 (Upload View)
            Route::get('/import', [MahasiswaController::class, 'importView'])->name('import.view');
            // Proses Upload & Parsing (Step 1 ke Step 2)
            Route::post('/import/upload', [MahasiswaController::class, 'uploadImport'])->name('import.upload');
            // Proses Simpan ke Database (Step 2 ke Result)
            Route::post('/import/save', [MahasiswaController::class, 'saveImport'])->name('import.save');
            // Halaman Hasil Akhir (Step 3)
            Route::get('/import/result', [MahasiswaController::class, 'importResult'])->name('import.result');
            // Downloads
            Route::get('/download-template', [MahasiswaController::class, 'downloadTemplate'])->name('download.template');
            Route::get('/download-result', [MahasiswaController::class, 'downloadImportResult'])->name('download.result');
        });
        // Di dalam group Route::prefix('mahasiswa')->name('mahasiswa.')

        // Hapus kata '/mahasiswa/' di URL dan hapus 'mahasiswa.' di name
        Route::get('/export-excel', [MahasiswaController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [MahasiswaController::class, 'exportPdf'])->name('export-pdf');
        // --- MANAJEMEN DATA (CRUD) ---
        Route::get('/kelola-data', [MahasiswaController::class, 'index'])->name('kelola-data');
        Route::get('/laporan', [MahasiswaController::class, 'laporan'])->name('laporan');

        Route::get('/create', [MahasiswaController::class, 'create'])->name('create')->middleware(['can:kelola-data-mahasiswa.create', 'check.crud:mahasiswa']);
        Route::post('/store', [MahasiswaController::class, 'store'])->name('store')->middleware(['can:kelola-data-mahasiswa.create', 'check.crud:mahasiswa']);

        // --- MAHASISWA KOMPETISI ---
        Route::get('/kompetisi-mahasiswa', [MahasiswaController::class, 'kompetisiIndex'])->name('kompetisi.index');
        Route::get('/kompetisi-mahasiswa/export-excel', [MahasiswaController::class, 'kompetisiExportExcel'])->name('kompetisi.export-excel');
        Route::get('/kompetisi-mahasiswa/export-pdf', [MahasiswaController::class, 'kompetisiExportPdf'])->name('kompetisi.export-pdf');
        Route::get('/kompetisi-mahasiswa/create', [MahasiswaController::class, 'kompetisiCreate'])->name('kompetisi.create')->middleware(['can:kelola-data-mahasiswa.create', 'check.crud:mahasiswa']);
        Route::post('/kompetisi-mahasiswa', [MahasiswaController::class, 'kompetisiStore'])->name('kompetisi.store')->middleware(['can:kelola-data-mahasiswa.create', 'check.crud:mahasiswa']);
        Route::delete('/kompetisi-mahasiswa/{id}', [MahasiswaController::class, 'kompetisiDestroy'])->name('kompetisi.destroy')->middleware(['can:kelola-data-mahasiswa.delete', 'check.crud:mahasiswa']);

        // --- IMPORT MAHASISWA KOMPETISI ---
        Route::middleware(['can:kelola-data-mahasiswa.create', 'check.crud:mahasiswa'])->group(function () {
            Route::get('/kompetisi-mahasiswa/import', [MahasiswaController::class, 'kompetisiImportView'])->name('kompetisi.import.view');
            Route::post('/kompetisi-mahasiswa/import/upload', [MahasiswaController::class, 'kompetisiUploadImport'])->name('kompetisi.import.upload');
            Route::post('/kompetisi-mahasiswa/import/save', [MahasiswaController::class, 'kompetisiSaveImport'])->name('kompetisi.import.save');
            Route::get('/kompetisi-mahasiswa/import/result', [MahasiswaController::class, 'kompetisiImportResult'])->name('kompetisi.import.result');
            Route::get('/kompetisi-mahasiswa/import/download-template', [MahasiswaController::class, 'kompetisiDownloadTemplate'])->name('kompetisi.import.download.template');
        });

        // Route dengan Parameter diletakkan di bawah agar tidak "memakan" route statis di atas
        Route::get('/{mahasiswa}', [MahasiswaController::class, 'show'])->name('show')->middleware('can:kelola-data-mahasiswa.detail');
        Route::get('/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('edit')->middleware(['can:kelola-data-mahasiswa.edit', 'check.crud:mahasiswa']);
        Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('update')->middleware(['can:kelola-data-mahasiswa.edit', 'check.crud:mahasiswa']);
        Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('destroy')->middleware(['can:kelola-data-mahasiswa.delete', 'check.crud:mahasiswa']);
    });
    // ============================
    // 🆕 Rekrutasi Dosen Routes (TAMBAHAN BARU)
    // ============================
    Route::prefix('rekrutasi-dosen')->name('rekrutasi-dosen.')->middleware('auth')->group(function () {
        // Overview / Main List
        Route::get('/', [RekrutasiDosenController::class, 'index'])->name('index')->middleware('can:rekrutasi-data-dosen.view');
        Route::get('/laporan', [RekrutasiDosenController::class, 'laporan'])->name('laporan')->middleware('can:rekrutasi-data-dosen.view');

        // Import Routes - Super Admin Only
        Route::middleware(['can:import-rekrutasi-dosen.view', 'check.crud:rekrutasi'])->group(function () {
            Route::get('/import', [RekrutasiDosenController::class, 'importView'])->name('import.view');
            Route::post('/import', [RekrutasiDosenController::class, 'import'])->name('import');
            // Import Routes - Detail
            Route::get('/import/template', [RekrutasiDosenController::class, 'downloadTemplate'])->name('import.template');
            Route::post('/import/upload', [RekrutasiDosenController::class, 'uploadImport'])->name('import.upload');
            Route::post('/import/save', [RekrutasiDosenController::class, 'saveImport'])->name('import.save');
            Route::get('/import/result', [RekrutasiDosenController::class, 'importResult'])->name('import.result');
            Route::get('/import/download-result', [RekrutasiDosenController::class, 'downloadImportResult'])->name('import.download-result');
        });

        // Jadwal Pengujian - Protected Routes
        Route::middleware('can:jadwal-pengujian.view')->group(function () {
            Route::get('/jadwal-pengujian', [RekrutasiDosenController::class, 'jadwalPengujian'])->name('jadwal-pengujian');
            Route::get('/jadwal-pengujian/export-excel', [RekrutasiDosenController::class, 'exportJadwalPengujianExcel'])->name('jadwal-pengujian.export-excel');
            Route::get('/jadwal-pengujian/export-csv', [RekrutasiDosenController::class, 'exportJadwalPengujianCsv'])->name('jadwal-pengujian.export-csv');
            Route::get('/jadwal-pengujian/export-pdf', [RekrutasiDosenController::class, 'exportJadwalPengujianPdf'])->name('jadwal-pengujian.export-pdf');

            // Detail - requires separate permission
            Route::get('/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'showJadwalPengujian'])->name('jadwal-pengujian.show')->middleware('can:jadwal-pengujian.detail');

            // Create/Edit/Delete - Super Admin only
            Route::post('/jadwal-pengujian', [RekrutasiDosenController::class, 'storeJadwalPengujian'])->name('jadwal-pengujian.store')->middleware('can:jadwal-pengujian.create');
            Route::get('/jadwal-pengujian/{id}/edit', [RekrutasiDosenController::class, 'editJadwalPengujian'])->name('jadwal-pengujian.edit')->middleware('can:jadwal-pengujian.edit');
            Route::match(['put', 'post'], '/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'updateJadwalPengujian'])->name('jadwal-pengujian.update')->middleware('can:jadwal-pengujian.edit');
            Route::delete('/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'destroyJadwalPengujian'])->name('jadwal-pengujian.destroy')->middleware('can:jadwal-pengujian.delete');
        });

        // Penilaian Calon Dosen - Protected Routes
        Route::middleware('can:penilaian-dosen.access')->group(function () {
            Route::get('/penilaian/{jadwal_id}', [RekrutasiDosenController::class, 'penilaian'])->name('penilaian');
            Route::get('/penilaian/export/{id}', [RekrutasiDosenController::class, 'exportPenilaianExcel'])->name('penilaian.export');
            Route::get('/penilaian/export-pdf/{id}', [RekrutasiDosenController::class, 'exportPenilaianPdf'])->name('penilaian.export-pdf');

            // Submit - requires separate permission (Dosen Penguji only, not Super Admin)
            Route::post('/penilaian/store', [RekrutasiDosenController::class, 'storePenilaian'])->name('penilaian.store')->middleware('can:penilaian-dosen.submit');
        });

        // Download Riwayat Pendidikan Files
        Route::get('/riwayat-file/{filename}', [RekrutasiDosenController::class, 'downloadRiwayatFile'])->name('riwayat.download')->middleware('can:rekrutasi-data-dosen.view');

        // Hasil Pengujian
        Route::middleware('can:hasil-pengujian.view')->group(function () {
            Route::get('/hasil-pengujian', [RekrutasiDosenController::class, 'hasilPengujian'])->name('hasil-pengujian');
            Route::get('/hasil-pengujian/combined-pdf/{calon_dosen_id}', [RekrutasiDosenController::class, 'hasilPengujianCombinedPdf'])->name('hasil-pengujian.combined-pdf');
            Route::get('/hasil-pengujian/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'publicDownloadBeritaAcara'])->name('hasil-pengujian.berita-acara');
        });

        // Berita Acara - Protected Routes (Dosen Penguji 1 & Super Admin only)
        Route::middleware('can:berita-acara.access')->group(function () {
            Route::get('/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'beritaAcara'])->name('berita-acara');
            Route::get('/berita-acara/{jadwal_id}/download', [RekrutasiDosenController::class, 'downloadBeritaAcara'])->name('berita-acara.download');

            // Submit - requires separate permission (Dosen Penguji 1 only, not Super Admin)
            Route::post('/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'storeBeritaAcara'])->name('berita-acara.store')->middleware('can:berita-acara.submit');
        });

        // ⚠️ EXPORT ROUTES - HARUS DI ATAS {id} ⚠️
        Route::get('/export-excel', [RekrutasiDosenController::class, 'exportExcel'])->name('export-excel')->middleware('can:rekrutasi-data-dosen.view');
        Route::get('/export-csv', [RekrutasiDosenController::class, 'exportCsv'])->name('export-csv')->middleware('can:rekrutasi-data-dosen.view');
        Route::get('/export-pdf', [RekrutasiDosenController::class, 'exportPdf'])->name('export-pdf')->middleware('can:rekrutasi-data-dosen.view');

        // CRUD Routes
        Route::get('/create', [RekrutasiDosenController::class, 'create'])->name('create')->middleware(['can:rekrutasi-data-dosen.view', 'check.crud:rekrutasi']);
        Route::post('/', [RekrutasiDosenController::class, 'store'])->name('store')->middleware(['can:rekrutasi-data-dosen.view', 'check.crud:rekrutasi']);
        Route::get('/{id}', [RekrutasiDosenController::class, 'show'])->name('show')->middleware('can:rekrutasi-data-dosen.view');
        Route::get('/{id}/edit', [RekrutasiDosenController::class, 'edit'])->name('edit')->middleware(['can:rekrutasi-data-dosen.view', 'check.crud:rekrutasi']);
        Route::put('/{id}', [RekrutasiDosenController::class, 'update'])->name('update')->middleware(['can:rekrutasi-data-dosen.view', 'check.crud:rekrutasi']);
        Route::delete('/{id}', [RekrutasiDosenController::class, 'destroy'])->name('destroy')->middleware(['can:rekrutasi-data-dosen.view', 'check.crud:rekrutasi']);
    });

    // ============================
    // Legacy Route Name Compatibility
    // ============================
    Route::get('/rekrutasi-dosen-legacy', [RekrutasiDosenController::class, 'index'])->name('rekrutasi-dosen')->middleware(['auth', 'can:rekrutasi-data-dosen.view']);

    Route::middleware(['auth', 'can:kelola-data-mahasiswa.create'])->group(function () {
        Route::get('/mahasiswa/kompetisi-mahasiswa/import', [MahasiswaController::class, 'kompetisiImportView'])->name('kompetisi.import.view');
        Route::post('/mahasiswa/kompetisi-mahasiswa/import/upload', [MahasiswaController::class, 'kompetisiUploadImport'])->name('kompetisi.import.upload');
        Route::post('/mahasiswa/kompetisi-mahasiswa/import/save', [MahasiswaController::class, 'kompetisiSaveImport'])->name('kompetisi.import.save');
        Route::get('/mahasiswa/kompetisi-mahasiswa/import/result', [MahasiswaController::class, 'kompetisiImportResult'])->name('kompetisi.import.result');
        Route::get('/mahasiswa/kompetisi-mahasiswa/import/download-template', [MahasiswaController::class, 'kompetisiDownloadTemplate'])->name('kompetisi.import.download.template');
    });

    // ============================
    // Data Routes (Backward Compatibility)
    // ============================
    Route::get('/data-dosen', [DosenController::class, 'index'])->name('data-dosen');

    // AJAX Endpoints
    Route::get('/api/prodi-by-fakultas/{fakultasId}', [DosenController::class, 'getProdiByFakultas'])
        ->name('prodi.by.fakultas');

    // ============================
    // Other Routes (unchanged)
    // ============================
    Route::get('/data-tpa', function () {
        return view('data.tpa');
    })->name('data-tpa');

    Route::get('/kelola-tpa', function () {
        return view('kelola-tpa');
    })->name('kelola-tpa');

    Route::get('/import-file-dosen', function () {
        return view('import-file-dosen');
    })->name('import-file-dosen');

    Route::get('/import-mahasiswa', function () {
        return view('import-mahasiswa');
    })->name('import-mahasiswa');

    Route::get('/import-tpa', function () {
        return view('import-tpa');
    })->name('import-tpa');

    Route::get('/dashboard-tpa', [TenagaPendukungAkademikController::class, 'dashboard'])->name('dashboard-tpa');

    // Kompetisi
    Route::get('/kompetisi', function () {
        return view('kompetisi.index');
    })->name('kompetisi');

    Route::get('dashboard-kompetisi', function () {
        $kompetisiTahun = \App\Models\Kompetisi::selectRaw('YEAR(tanggal_kompetisi) as year, tingkat_kompetisi, count(*) as count')
            ->groupBy('year', 'tingkat_kompetisi')
            ->get();

        $juaraTahun = \Illuminate\Support\Facades\DB::table('mahasiswa_kompetisi')
            ->join('kompetisi', 'mahasiswa_kompetisi.kompetisi_id', '=', 'kompetisi.id')
            ->selectRaw('YEAR(kompetisi.tanggal_kompetisi) as year, mahasiswa_kompetisi.juara, count(*) as count')
            ->whereNotNull('mahasiswa_kompetisi.juara')
            ->groupBy('year', 'mahasiswa_kompetisi.juara')
            ->get();

        $kompetisiProdi = \Illuminate\Support\Facades\DB::table('mahasiswa_kompetisi')
            ->join('mahasiswa', 'mahasiswa_kompetisi.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select('prodi.nama_prodi', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('prodi.nama_prodi')
            ->get();

        $kompetisiKategori = \App\Models\Kompetisi::select('jenis', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('jenis')
            ->get();

        // New real-time student and competition metrics
        $mahasiswaProdi = \App\Models\Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select('prodi.nama_prodi', DB::raw('count(*) as count'))
            ->groupBy('prodi.nama_prodi')
            ->pluck('count', 'prodi.nama_prodi')
            ->toArray();

        $mahasiswaStatus = \App\Models\Mahasiswa::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $mahasiswaAngkatan = \App\Models\Mahasiswa::select(DB::raw("CONCAT('20', SUBSTRING(nim, 1, 2)) as angkatan"), DB::raw('count(*) as count'))
            ->groupBy('angkatan')
            ->pluck('count', 'angkatan')
            ->toArray();

        $juaraDoughnut = DB::table('mahasiswa_kompetisi')
            ->select('juara', DB::raw('count(*) as count'))
            ->whereIn('juara', ['Juara 1', 'Juara 2', 'Juara 3'])
            ->groupBy('juara')
            ->pluck('count', 'juara')
            ->toArray();

        $jenisDoughnut = \App\Models\Kompetisi::select('jenis', DB::raw('count(*) as count'))
            ->groupBy('jenis')
            ->pluck('count', 'jenis')
            ->toArray();

        return view('dashboard-kompetisi', compact(
            'kompetisiTahun',
            'juaraTahun',
            'kompetisiProdi',
            'kompetisiKategori',
            'mahasiswaProdi',
            'mahasiswaStatus',
            'mahasiswaAngkatan',
            'juaraDoughnut',
            'jenisDoughnut'
        ));
    })->name('dashboard-kompetisi');

    // Management
    Route::get('/manajemen-tpa', function () {
        return view('manajemen.tpa');
    })->name('manajemen-tpa');

    Route::get('/manajemen-mahasiswa', function () {
        return view('kelola-mahasiswa');
    })->name('manajemen-mahasiswa');

    // Reports
    Route::get('/master-data', function () {
        return view('reports.master-data');
    })->name('master-data');

    // System - Pengaturan - Konfigurasi Sistem
    Route::get('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'index'])
        ->name('pengaturan')
        ->middleware('permission:konfigurasi-sistem.view');
    Route::get('/pengaturan/notifikasi', [App\Http\Controllers\PengaturanController::class, 'notifikasi'])
        ->name('pengaturan.notifikasi')
        ->middleware('permission:pengaturan-notifikasi.view');
    Route::put('/pengaturan/notifikasi', [App\Http\Controllers\PengaturanController::class, 'updateNotifikasi'])
        ->name('pengaturan.notifikasi.update')
        ->middleware('permission:pengaturan-notifikasi.edit');
    Route::get('/pengaturan/periode', [App\Http\Controllers\PengaturanController::class, 'periode'])
        ->name('pengaturan.periode')
        ->middleware('permission:pengaturan-periode.view');
    Route::put('/pengaturan/periode', [App\Http\Controllers\PengaturanController::class, 'updatePeriode'])
        ->name('pengaturan.periode.update')
        ->middleware('permission:pengaturan-periode.edit');
    Route::post('/pengaturan/role', [App\Http\Controllers\PengaturanController::class, 'storeRole'])
        ->name('pengaturan.role.store')
        ->middleware('permission:konfigurasi-sistem.create');
    Route::put('/pengaturan/role/{id}', [App\Http\Controllers\PengaturanController::class, 'updateRole'])
        ->name('pengaturan.role.update')
        ->middleware('permission:konfigurasi-sistem.edit');
    Route::delete('/pengaturan/role/{id}', [App\Http\Controllers\PengaturanController::class, 'destroyRole'])
        ->name('pengaturan.role.destroy')
        ->middleware('permission:konfigurasi-sistem.delete');

    // Pengaturan - Plotting Permission
    Route::get('/pengaturan/plotting/{roleId}', [App\Http\Controllers\PengaturanController::class, 'plotting'])->name('pengaturan.plotting');
    Route::put('/pengaturan/plotting/{roleId}/update', [App\Http\Controllers\PengaturanController::class, 'updatePermissions'])->name('pengaturan.plotting.update');

    // Pengaturan - Plotting Permission Export
    Route::get('/pengaturan/plotting/{roleId}/export/excel', [App\Http\Controllers\PengaturanController::class, 'exportPlottingExcel'])->name('pengaturan.plotting.export.excel');
    Route::get('/pengaturan/plotting/{roleId}/export/csv', [App\Http\Controllers\PengaturanController::class, 'exportPlottingCsv'])->name('pengaturan.plotting.export.csv');
    Route::get('/pengaturan/plotting/{roleId}/export/pdf', [App\Http\Controllers\PengaturanController::class, 'exportPlottingPdf'])->name('pengaturan.plotting.export.pdf');

    // Pengaturan - User Management
    Route::get('/pengaturan/user-management', [App\Http\Controllers\PengaturanController::class, 'userManagement'])
        ->name('pengaturan.user-management')
        ->middleware('permission:user-management.view');
    Route::post('/pengaturan/user', [App\Http\Controllers\PengaturanController::class, 'storeUser'])
        ->name('pengaturan.user.store')
        ->middleware('permission:user-management.create');
    Route::put('/pengaturan/user/{id}', [App\Http\Controllers\PengaturanController::class, 'updateUser'])
        ->name('pengaturan.user.update')
        ->middleware('permission:user-management.edit');
    Route::delete('/pengaturan/user/{id}', [App\Http\Controllers\PengaturanController::class, 'destroyUser'])
        ->name('pengaturan.user.destroy')
        ->middleware('permission:user-management.delete');

    // Pengaturan - User Export
    Route::get('/pengaturan/user/export/excel', [App\Http\Controllers\PengaturanController::class, 'exportUserExcel'])
        ->name('pengaturan.user.export.excel')
        ->middleware('permission:user-management.view');
    Route::get('/pengaturan/user/export/csv', [App\Http\Controllers\PengaturanController::class, 'exportUserCsv'])
        ->name('pengaturan.user.export.csv')
        ->middleware('permission:user-management.view');
    Route::get('/pengaturan/user/export/pdf', [App\Http\Controllers\PengaturanController::class, 'exportUserPdf'])
        ->name('pengaturan.user.export.pdf')
        ->middleware('permission:user-management.view');

    // Pengaturan - Konfigurasi Sistem Export
    Route::get('/pengaturan/export/excel', [App\Http\Controllers\PengaturanController::class, 'exportExcel'])
        ->name('pengaturan.export.excel')
        ->middleware('permission:konfigurasi-sistem.view');
    Route::get('/pengaturan/export/csv', [App\Http\Controllers\PengaturanController::class, 'exportCsv'])
        ->name('pengaturan.export.csv')
        ->middleware('permission:konfigurasi-sistem.view');
    Route::get('/pengaturan/export/pdf', [App\Http\Controllers\PengaturanController::class, 'exportPdf'])
        ->name('pengaturan.export.pdf')
        ->middleware('permission:konfigurasi-sistem.view');
});
