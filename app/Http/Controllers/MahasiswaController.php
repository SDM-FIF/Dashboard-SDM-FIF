<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for logging
use Maatwebsite\Excel\Facades\Excel;
class MahasiswaController extends Controller
{
    /**
     * Display a listing of Mahasiswa.
     */
    public function index(Request $request)
    {
        $this->authorize('kelola-data-mahasiswa.view');
        
        // Eager load relasi prodi untuk efisiensi query
        $query = Mahasiswa::with('prodi');

        // Filter Program Studi
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search nama atau NIM
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
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

        $mahasiswa = $query->paginate(15)->withQueryString();

        // Data untuk dropdown filter
        $filterData = [
            'prodi' => Prodi::all(),
            'status' => ['aktif', 'cuti', 'nonaktif', 'lulus', 'resign', 'dikeluarkan'], // Kecil semua
        ];

        return view('mahasiswa.kelola-data', compact('mahasiswa', 'filterData'));
    }

    public function kelolaData(Request $request)
    {
        return $this->index($request);
    }

    // =========================================================================
    // START: FEATURE IMPORT (ATM from RekrutasiDosenController)
    // =========================================================================

    /**
     * Menampilkan View Import (Multi-step)
     */
    public function importView(Request $request)
    {
        $this->authorize('import-data-mahasiswa.view');
        
        $step = $request->get('step');
        $reset = $request->get('reset');

        // 1. Logika Reset (Dahulukan ini agar session bersih sebelum dicek)
        if ($reset == '1') {
            session()->forget(['import_data', 'show_import', 'import_result', 'file_uploaded']);
            return redirect()->route('mahasiswa.import.view', ['step' => 1]);
        }

        // 2. Deteksi Step Otomatis jika URL tidak punya parameter ?step=
        if (!$step) {
            if (session()->has('import_result')) {
                // Jika sudah ada hasil, langsung lempar ke route result
                return redirect()->route('mahasiswa.import.result');
            } elseif (session()->has('import_data')) {
                $step = 2;
            } else {
                $step = 1;
            }
        }

        // 3. Jika user paksa balik ke Step 1 via tombol/link, bersihkan data preview
        if ($step == 1) {
            session()->forget(['import_data', 'show_import']);
        }

        return view('mahasiswa.import-data', compact('step'));
    }

    /**
     * Upload dan Parsing File
     */
    public function uploadImport(Request $request)
    {
        $this->authorize('import-data-mahasiswa.view');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $data = $this->parseImportFile($file);

            if (empty($data)) {
                return redirect()->back()
                    ->with('error', 'File kosong atau format tidak sesuai.');
            }

            // Validasi data
            $validatedData = $this->validateImportData($data);

            session([
                'import_data' => $validatedData,
                'show_import' => true,
                'file_uploaded' => true
            ]);

            $validCount = collect($validatedData)->where('is_valid', true)->count();
            $totalCount = count($validatedData);

            return redirect()->route('mahasiswa.import.view', ['step' => 2])
                ->with('success', "File berhasil diupload! {$validCount} dari {$totalCount} data valid.");

        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error upload file: ' . $e->getMessage());
        }
    }

    /**
     * Parse File (Excel/CSV)
     */
    private function parseImportFile($file)
    {
        try {
            // Menggunakan Facade Excel untuk membaca file ke dalam array
            $dataRaw = Excel::toArray([], $file);
            $rows = $dataRaw[0] ?? [];

            // Hapus header
            array_shift($rows);

            $data = [];
            foreach ($rows as $row) {
                if (!empty(trim($row[0] ?? ''))) {
                    $data[] = [
                        'nim' => trim($row[0] ?? ''),
                        'nama_lengkap' => trim($row[1] ?? ''),
                        'prodi' => trim($row[2] ?? ''),
                        'status' => trim($row[3] ?? ''),
                    ];
                }
            }
            return $data;
        } catch (\Exception $e) {
            Log::error('Excel parse error: ' . $e->getMessage());
            return [];
        }
    }
    /**
     * Validasi Data Import
     */
    private function validateImportData($data)
    {
        $validated = [];
        $validStatuses = ['aktif', 'cuti', 'nonaktif', 'lulus', 'resign', 'dikeluarkan'];

        foreach ($data as $row) {
            $errors = [];

            // 1. Validasi NIM
            if (empty($row['nim'])) {
                $errors[] = 'NIM kosong';
            } elseif (Mahasiswa::where('nim', $row['nim'])->exists()) {
                $errors[] = 'NIM sudah terdaftar';
            } elseif (!is_numeric($row['nim'])) {
                $errors[] = 'NIM harus angka';
            }

            // 2. Validasi Nama
            if (empty($row['nama_lengkap'])) {
                $errors[] = 'Nama kosong';
            }

            // 3. Validasi Prodi (Lookup by Name)
            $prodiId = null;
            if (!empty($row['prodi'])) {
                $prodi = Prodi::where('nama_prodi', $row['prodi'])->first();
                if ($prodi) {
                    $prodiId = $prodi->id;
                } else {
                    $errors[] = 'Prodi tidak ditemukan (Pastikan penulisan sama)';
                }
            } else {
                $errors[] = 'Prodi kosong';
            }

            // 4. Validasi Status
            $statusInput = strtolower(trim($row['status']));
            if (empty($row['status'])) {
                $errors[] = 'Status kosong';
            } elseif (!in_array($statusInput, $validStatuses)) {
                $errors[] = 'Status tidak valid (Gunakan: aktif, cuti, nonaktif, lulus, resign, atau dikeluarkan)';
            }

            $validated[] = [
                'nim' => $row['nim'],
                'nama_lengkap' => $row['nama_lengkap'],
                'prodi_name' => $row['prodi'],
                'prodi_id' => $prodiId,
                'status' => $statusInput,
                'is_valid' => empty($errors),
                'errors' => $errors
            ];
        }
        return $validated;
    }
    /**
     * Simpan Data Import ke Database
     */
    public function saveImport(Request $request)
    {
        $this->authorize('import-data-mahasiswa.view');
        
        $importData = session('import_data', []);

        if (empty($importData)) {
            return redirect()->route('mahasiswa.import.view')
                ->with('error', 'Tidak ada data untuk diimport.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($importData as $row) {
            if ($row['is_valid']) {
                try {
                    Mahasiswa::create([
                        'nim' => $row['nim'],
                        'nama_lengkap' => $row['nama_lengkap'],
                        'prodi_id' => $row['prodi_id'],
                        'status' => $row['status'],
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                }
            } else {
                $failCount++;
            }
        }

        // Simpan hasil ke session untuk halaman result
        session([
            'import_result' => [
                'success' => $successCount,
                'failed' => $failCount,
                'data' => array_filter($importData, fn($row) => $row['is_valid']) // Data yang berhasil
            ]
        ]);

        session()->forget('import_data');

        return redirect()->route('mahasiswa.import.result')
            ->with('success', "Import selesai! {$successCount} sukses, {$failCount} gagal.");
    }

    /**
     * Halaman Hasil Import
     */
    public function importResult()
    {
        $this->authorize('import-data-mahasiswa.view');
        
        $result = session('import_result', []);
        return view('mahasiswa.import-result', compact('result'));
    }

    /**
     * Download Template Import Mahasiswa
     */
    public function downloadTemplate()
    {
        $this->authorize('import-data-mahasiswa.view');
        
        $filename = 'template-mahasiswa.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () {
            echo $this->generateTemplateExcel();
        }, 200, $headers);
    }

    /**
     * Generate XML Excel Template
     */
    private function generateTemplateExcel()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <?mso-application progid="Excel.Sheet"?>
        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
         xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
         <Styles>
          <Style ss:ID="Header">
           <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
           <Interior ss:Color="#4472C4" ss:Pattern="Solid"/>
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
            <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
            <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
            <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
           </Borders>
          </Style>
         </Styles>
         <Worksheet ss:Name="Template Mahasiswa">
          <Table>
           <Column ss:Width="100"/>
           <Column ss:Width="200"/>
           <Column ss:Width="150"/>
           <Column ss:Width="100"/>
           <Row ss:Height="25">
            <Cell ss:StyleID="Header"><Data ss:Type="String">NIM</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Nama Lengkap</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Status (aktif/cuti/nonaktif/lulus/resign/dikeluarkan)</Data></Cell>
           </Row>
          </Table>
         </Worksheet>
        </Workbook>';
    }

    /**
     * Download Hasil Import (Log Sukses)
     */
    public function downloadImportResult()
    {        $this->authorize('import-data-mahasiswa.view');
                $result = session('import_result', []);

        if (empty($result['data'])) {
            return redirect()->back()->with('error', 'Tidak ada data untuk didownload.');
        }

        $filename = 'hasil-import-mahasiswa-' . date('Y-m-d-His') . '.xls';
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($result) {
            echo $this->generateResultExcel($result['data']);
        }, 200, $headers);
    }

    private function generateResultExcel($data)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <?mso-application progid="Excel.Sheet"?>
        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
         xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
         <Styles>
          <Style ss:ID="Header">
           <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
           <Interior ss:Color="#28a745" ss:Pattern="Solid"/>
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
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
           <Column ss:Width="100"/>
           <Column ss:Width="200"/>
           <Column ss:Width="150"/>
           <Column ss:Width="100"/>
           <Row ss:Height="25">
            <Cell ss:StyleID="Header"><Data ss:Type="String">NIM</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Nama Lengkap</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Program Studi</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Status</Data></Cell>
           </Row>';

        foreach ($data as $row) {
            $xml .= '
           <Row>
            <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nim'], ENT_XML1, 'UTF-8') . '</Data></Cell>
            <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nama_lengkap'], ENT_XML1, 'UTF-8') . '</Data></Cell>
            <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_name'], ENT_XML1, 'UTF-8') . '</Data></Cell>
            <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['status'], ENT_XML1, 'UTF-8') . '</Data></Cell>
           </Row>';
        }

        $xml .= '
          </Table>
         </Worksheet>
        </Workbook>';

        return $xml;
    }

    // =========================================================================
    // END: FEATURE IMPORT
    // =========================================================================

    // ... (Sisa method create, store, update, destroy tetap sama seperti kode awal Anda)

    public function create()
    {
        $this->authorize('kelola-data-mahasiswa.create');
        
        $prodi = Prodi::all();
        return view('mahasiswa.tambah-data', compact('prodi'));
    }

    public function store(Request $request)
    {
        $this->authorize('kelola-data-mahasiswa.create');
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim',
            'prodi_id' => 'required|exists:prodi,id',
            'status' => 'required|in:aktif,cuti,nonaktif,lulus,resign,dikeluarkan', // SINKRON DISINI
        ]);

        try {
            Mahasiswa::create($validated);
            return redirect()->route('mahasiswa.kelola-data')->with('success', 'Data Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }
    public function show(Request $request, Mahasiswa $mahasiswa)
    {
        $this->authorize('kelola-data-mahasiswa.detail');
        
        // 1. Ambil detail mahasiswa yang dipilih (Eager load relasi)
        $mahasiswa->load(['prodi']);

        // 2. Ambil data mahasiswa keseluruhan untuk tabel di bawah detail
        $allQuery = Mahasiswa::query();

        // Tambahkan filter status jika ada (Sesuai form filter di Blade kamu)
        if ($request->filled('filter_status')) {
            $allQuery->where('status', $request->filter_status);
        }

        $allMahasiswa = $allQuery->paginate(10)->withQueryString();

        // 3. Kirim kedua variabel ke view
        return view('mahasiswa.detail-data', compact('mahasiswa', 'allMahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $this->authorize('kelola-data-mahasiswa.edit');
        
        $prodi = Prodi::all();
        return view('mahasiswa.edit-data', compact('mahasiswa', 'prodi'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $this->authorize('kelola-data-mahasiswa.edit');
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim,' . $mahasiswa->id,
            'prodi_id' => 'required|exists:prodi,id',
            'status' => 'required|in:aktif,cuti,nonaktif,lulus,resign,dikeluarkan', // SINKRON DISINI
        ]);

        try {
            $mahasiswa->update($validated);
            return redirect()->route('mahasiswa.kelola-data')->with('success', 'Data Mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $this->authorize('kelola-data-mahasiswa.delete');
        
        try {
            $mahasiswa->delete();
            return redirect()->route('mahasiswa.kelola-data')->with('success', 'Data Mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function laporan()
    {
        $statistik = [
            'total' => Mahasiswa::count(),
            'per_status' => Mahasiswa::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status'),
            'per_prodi' => Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->selectRaw('prodi.nama_prodi, COUNT(mahasiswa.id) as total')
                ->groupBy('prodi.nama_prodi')
                ->pluck('total', 'nama_prodi'),
        ];
        return view('mahasiswa.laporan', compact('statistik'));
    }
}