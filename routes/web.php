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

// ============================
// Landing & Welcome
// ============================
Route::get('/', function () {
    return view('landingpage');
})->name('landingpage');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

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
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Dashboard Dosen
    Route::get('/dashboard-dosen', function () {
        return view('dashboard-dosen');
    })->name('dashboard-dosen');

    // ============================
    // 🆕 MASTER DATA ROUTES (Fakultas, Prodi, Kompetisi)
    // ============================
    // Menggunakan Route::resource agar otomatis ada index, create, store, edit, update, destroy
    Route::prefix('master-data')->group(function () {
        
        // URL: /master-data/fakultas
        // Route Name: fakultas.index, fakultas.create, fakultas.edit, dst.
        Route::resource('fakultas', FakultasController::class);

        // URL: /master-data/prodi
        // Route Name: prodi.index, dst.
        Route::resource('prodi', ProdiController::class);

        // URL: /master-data/kompetisi
        // Route Name: kompetisi.index, dst.
        Route::resource('kompetisi', KompetisiController::class);
    });
    // ============================
    // Manajemen Dosen Routes
    // ============================
    Route::prefix('manajemen-dosen')->name('manajemen-dosen.')->group(function () {
        Route::get('/kelola-data', [DosenController::class, 'kelolaData'])->name('kelola-data');
        Route::get('/export-excel', [DosenController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-csv', [DosenController::class, 'exportCsv'])->name('export-csv');
        Route::get('/export-pdf', [DosenController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export', [DosenController::class, 'exportExcel'])->name('export'); // Backward compatibility
        Route::get('/import-data', [DosenController::class, 'importForm'])->name('import-data');
        Route::post('/import-data', [DosenController::class, 'importProcess'])->name('import-process');
        Route::get('/laporan', [DosenController::class, 'laporan'])->name('laporan');

        // CRUD Routes
        Route::get('/create', [DosenController::class, 'create'])->name('create');
        Route::post('/store', [DosenController::class, 'store'])->name('store');
        Route::get('/{dosen}', [DosenController::class, 'show'])->name('show');
        Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('edit');
        Route::put('/{dosen}', [DosenController::class, 'update'])->name('update');
        Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('destroy');
    });

    // ============================
    // Manajemen TPA Routes
    // ============================
    Route::prefix('manajemen-tpa')->name('manajemen-tpa.')->group(function () {
        // Kelola Data & Laporan
        Route::get('/kelola-data', [TenagaPendukungAkademikController::class, 'kelolaData'])->name('kelola-data');
        Route::get('/laporan', [TenagaPendukungAkademikController::class, 'laporan'])->name('laporan');


        Route::get('/download-template', [TenagaPendukungAkademikController::class, 'downloadTemplate'])->name('download-template');

        // Import Features (Upload -> Preview -> Store)
        Route::get('/import-data', [TenagaPendukungAkademikController::class, 'importForm'])->name('import-data');
        Route::post('/import-process', [TenagaPendukungAkademikController::class, 'importProcess'])->name('import-process');
        Route::post('/import-store', [TenagaPendukungAkademikController::class, 'storeImport'])->name('import.store');

        // CRUD Routes
        Route::get('/create', [TenagaPendukungAkademikController::class, 'create'])->name('create');
        Route::post('/store', [TenagaPendukungAkademikController::class, 'store'])->name('store');

        // Letakkan route dengan parameter {tpa} di paling bawah agar tidak bentrok dengan route statis
        Route::get('/{tpa}', [TenagaPendukungAkademikController::class, 'show'])->name('show');
        Route::get('/{tpa}/edit', [TenagaPendukungAkademikController::class, 'edit'])->name('edit');
        Route::put('/{tpa}', [TenagaPendukungAkademikController::class, 'update'])->name('update');
        Route::delete('/{tpa}', [TenagaPendukungAkademikController::class, 'destroy'])->name('destroy');
    });
    // ============================
    // Manajemen Mahasiswa Routes
    // ============================

    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        // --- FITUR IMPORT (Pola Multi-Step) ---
        // Halaman utama import & Step 1 (Upload View)
        Route::get('/import', [MahasiswaController::class, 'importView'])->name('import.view');
        // Proses Upload & Parsing (Step 1 ke Step 2)
        Route::post('/import/upload', [MahasiswaController::class, 'uploadImport'])->name('import.upload');
        // Proses Simpan ke Database (Step 2 ke Result)
        Route::post('/import/save', [MahasiswaController::class, 'saveImport'])->name('import.save');
        // Halaman Hasil Akhir (Step 3)
        Route::get('/import/result', [MahasiswaController::class, 'importResult'])->name('import.result');

        // --- DOWNLOADS ---
        Route::get('/download-template', [MahasiswaController::class, 'downloadTemplate'])->name('download.template');
        Route::get('/download-result', [MahasiswaController::class, 'downloadImportResult'])->name('download.result');

        // --- MANAJEMEN DATA (CRUD) ---
        Route::get('/kelola-data', [MahasiswaController::class, 'index'])->name('kelola-data');
        Route::get('/laporan', [MahasiswaController::class, 'laporan'])->name('laporan');

        Route::get('/create', [MahasiswaController::class, 'create'])->name('create');
        Route::post('/store', [MahasiswaController::class, 'store'])->name('store');

        // Route dengan Parameter diletakkan di bawah agar tidak "memakan" route statis di atas
        Route::get('/{mahasiswa}', [MahasiswaController::class, 'show'])->name('show');
        Route::get('/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('edit');
        Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('update');
        Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('destroy');
    });
    // ============================
    // 🆕 Rekrutasi Dosen Routes (TAMBAHAN BARU)
    // ============================
    Route::prefix('rekrutasi-dosen')->name('rekrutasi-dosen.')->group(function () {
        // Overview / Main List
        Route::get('/', [RekrutasiDosenController::class, 'index'])->name('index');

        // Import Routes
        Route::get('/import', [RekrutasiDosenController::class, 'importView'])->name('import.view');
        Route::post('/import', [RekrutasiDosenController::class, 'import'])->name('import');
        // Import Routes - Detail
        Route::get('/import/template', [RekrutasiDosenController::class, 'downloadTemplate'])->name('import.template');
        Route::post('/import/upload', [RekrutasiDosenController::class, 'uploadImport'])->name('import.upload');
        Route::post('/import/save', [RekrutasiDosenController::class, 'saveImport'])->name('import.save');
        Route::get('/import/result', [RekrutasiDosenController::class, 'importResult'])->name('import.result');
        Route::get('/import/download-result', [RekrutasiDosenController::class, 'downloadImportResult'])->name('import.download-result');

        // Jadwal Pengujian
        Route::get('/jadwal-pengujian', [RekrutasiDosenController::class, 'jadwalPengujian'])->name('jadwal-pengujian');
        Route::post('/jadwal-pengujian', [RekrutasiDosenController::class, 'storeJadwalPengujian'])->name('jadwal-pengujian.store');
        Route::get('/jadwal-pengujian/export-excel', [RekrutasiDosenController::class, 'exportJadwalPengujianExcel'])->name('jadwal-pengujian.export-excel');
        Route::get('/jadwal-pengujian/export-csv', [RekrutasiDosenController::class, 'exportJadwalPengujianCsv'])->name('jadwal-pengujian.export-csv');
        Route::get('/jadwal-pengujian/export-pdf', [RekrutasiDosenController::class, 'exportJadwalPengujianPdf'])->name('jadwal-pengujian.export-pdf');
        Route::get('/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'showJadwalPengujian'])->name('jadwal-pengujian.show');
        Route::get('/jadwal-pengujian/{id}/edit', [RekrutasiDosenController::class, 'editJadwalPengujian'])->name('jadwal-pengujian.edit');
        Route::match(['put', 'post'], '/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'updateJadwalPengujian'])->name('jadwal-pengujian.update');
        Route::delete('/jadwal-pengujian/{id}', [RekrutasiDosenController::class, 'destroyJadwalPengujian'])->name('jadwal-pengujian.destroy');
        
        // Penilaian Calon Dosen
        Route::get('/penilaian/export/{id}', [RekrutasiDosenController::class, 'exportPenilaianExcel'])->name('penilaian.export');
        Route::get('/penilaian/export-pdf/{id}', [RekrutasiDosenController::class, 'exportPenilaianPdf'])->name('penilaian.export-pdf');
        Route::post('/penilaian/store', [RekrutasiDosenController::class, 'storePenilaian'])->name('penilaian.store');
        Route::get('/penilaian/{jadwal_id}', [RekrutasiDosenController::class, 'penilaian'])->name('penilaian');

        // Download Riwayat Pendidikan Files
        Route::get('/riwayat-file/{filename}', [RekrutasiDosenController::class, 'downloadRiwayatFile'])->name('riwayat.download');

        // Hasil Pengujian
        Route::get('/hasil-pengujian', [RekrutasiDosenController::class, 'hasilPengujian'])->name('hasil-pengujian');
        Route::get('/hasil-pengujian/combined-pdf/{calon_dosen_id}', [RekrutasiDosenController::class, 'hasilPengujianCombinedPdf'])->name('hasil-pengujian.combined-pdf');
        Route::get('/hasil-pengujian/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'publicDownloadBeritaAcara'])->name('hasil-pengujian.berita-acara');

        // Berita Acara (only accessible by Dosen Penguji 1)
        Route::get('/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'beritaAcara'])->name('berita-acara');
        Route::post('/berita-acara/{jadwal_id}', [RekrutasiDosenController::class, 'storeBeritaAcara'])->name('berita-acara.store');
        Route::get('/berita-acara/{jadwal_id}/download', [RekrutasiDosenController::class, 'downloadBeritaAcara'])->name('berita-acara.download');

        // ⚠️ EXPORT ROUTES - HARUS DI ATAS {id} ⚠️
        Route::get('/export-excel', [RekrutasiDosenController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-csv', [RekrutasiDosenController::class, 'exportCsv'])->name('export-csv');
        Route::get('/export-pdf', [RekrutasiDosenController::class, 'exportPdf'])->name('export-pdf');

        // CRUD Routes
        Route::get('/create', [RekrutasiDosenController::class, 'create'])->name('create');
        Route::post('/', [RekrutasiDosenController::class, 'store'])->name('store');
        Route::get('/{id}', [RekrutasiDosenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RekrutasiDosenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RekrutasiDosenController::class, 'update'])->name('update');
        Route::delete('/{id}', [RekrutasiDosenController::class, 'destroy'])->name('destroy');
    });

    // ============================
    // Backward Compatibility Routes (untuk Navbar)
    // ============================
    // UBAH route ini dari yang lama
    Route::get('/rekrutasi-dosen', [RekrutasiDosenController::class, 'index'])->name('rekrutasi-dosen');

    // UBAH route ini dari yang lama
    Route::get('/import-rekruitasi', [RekrutasiDosenController::class, 'importView'])->name('import-rekruitasi');

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

    Route::get('/import-dosen', function () {
        return view('import-dosen');
    })->name('import-dosen');

    Route::get('/import-file-dosen', function () {
        return view('import-file-dosen');
    })->name('import-file-dosen');

    Route::get('/import-mahasiswa', function () {
        return view('import-mahasiswa');
    })->name('import-mahasiswa');

    Route::get('/import-tpa', function () {
        return view('import-tpa');
    })->name('import-tpa');

    Route::get('/dashboard-tpa', function () {
        return view('dashboard-tpa');
    })->name('dashboard-tpa');

    // Kompetisi
    Route::get('/kompetisi', function () {
        return view('kompetisi.index');
    })->name('kompetisi');

    Route::get('dashboard-kompetisi', function () {
        return view('dashboard-kompetisi');
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

    // System
    Route::get('/pengaturan', function () {
        return view('system.pengaturan');
    })->name('pengaturan');
});
