<?php

namespace App\Http\Controllers;

use App\Models\CalonDosen;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekrutasiDosenExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nomor_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'bidang_keahlian' => 'nullable|string',
        ]);

        // No registrasi auto-generate via model boot
        CalonDosen::create($validated);

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $rekrutasi = CalonDosen::with(['prodi', 'jadwalPengujian.dosenPenguji'])->findOrFail($id);

        // UBAH PATH VIEW DI SINI
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
        $rekrutasi = CalonDosen::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nomor_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'bidang_keahlian' => 'nullable|string',
        ]);

        $rekrutasi->update($validated);

        return redirect()->route('rekrutasi-dosen')
            ->with('success', 'Data rekrutasi berhasil diupdate!');
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

    public function jadwalPengujian()
    {
        // UBAH PATH VIEW DI SINI
        return view('rekrutasi-dosen.jadwal-pengujian-dosen');
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
 </Styles>
 <Worksheet ss:Name="Template Rekrutasi Dosen">
  <Table>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="120"/>
   <Column ss:Width="120"/>
   <Column ss:Width="150"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">No. Registrasi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jenis Kelamin</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tahun Ajar</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi</Data></Cell>
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

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 5) {
                    // Include row even if fields are empty for validation
                    $hasData = false;
                    foreach ($row as $cell) {
                        if (!empty(trim($cell))) {
                            $hasData = true;
                            break;
                        }
                    }
                    
                    if ($hasData) {
                        $data[] = [
                            'no_registrasi' => trim($row[0]),
                            'nama_calon' => trim($row[1]),
                            'jenis_kelamin' => trim($row[2]),
                            'tahun_ajar' => trim($row[3]),
                            'prodi' => trim($row[4]),
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

                // Skip header row
                array_shift($rows);

                foreach ($rows as $row) {
                    if (count($row) >= 5) {
                        // Include row even if fields are empty for validation
                        $hasData = false;
                        foreach ($row as $cell) {
                            if (!empty(trim($cell))) {
                                $hasData = true;
                                break;
                            }
                        }
                        
                        if ($hasData) {
                            $data[] = [
                                'no_registrasi' => trim($row[0] ?? ''),
                                'nama_calon' => trim($row[1] ?? ''),
                                'jenis_kelamin' => trim($row[2] ?? ''),
                                'tahun_ajar' => trim($row[3] ?? ''),
                                'prodi' => trim($row[4] ?? ''),
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
        $validTahunAjar = [
            'Ganjil 2024/2025',
            'Genap 2024/2025',
            'Ganjil 2025/2026',
            'Genap 2025/2026',
        ];

        foreach ($data as $index => $row) {
            $errors = [];

            // Validate No. Registrasi
            if (empty($row['no_registrasi'])) {
                $errors[] = 'No. Registrasi kosong';
            } elseif (CalonDosen::where('no_registrasi', $row['no_registrasi'])->exists()) {
                $errors[] = 'No. Registrasi sudah ada';
            }

            // Validate Nama
            if (empty($row['nama_calon'])) {
                $errors[] = 'Nama kosong';
            }

            // Validate Jenis Kelamin
            if (!in_array($row['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
                $errors[] = 'Jenis kelamin tidak valid';
            }

            // Validate Tahun Ajar
            if (empty($row['tahun_ajar'])) {
                $errors[] = 'Tahun ajar kosong';
            } elseif (!in_array($row['tahun_ajar'], $validTahunAjar)) {
                $errors[] = 'Tahun ajar tidak valid';
            }

            // Validate Prodi
            $prodiId = null;
            if (!empty($row['prodi'])) {
                $prodi = Prodi::where('nama_prodi', $row['prodi'])->first();
                if ($prodi) {
                    $prodiId = $prodi->id;
                } else {
                    $errors[] = 'Prodi tidak ditemukan';
                }
            } else {
                $errors[] = 'Prodi kosong';
            }

            $validated[] = [
                'no_registrasi' => $row['no_registrasi'],
                'nama_calon' => $row['nama_calon'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tahun_ajar' => $row['tahun_ajar'],
                'prodi_name' => $row['prodi'],
                'prodi_id' => $prodiId,
                'is_valid' => empty($errors),
                'errors' => $errors
            ];
        }

        return $validated;
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

        foreach ($importData as $row) {
            if ($row['is_valid']) {
                try {
                    CalonDosen::create([
                        'no_registrasi' => $row['no_registrasi'],
                        'nama' => $row['nama_calon'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'prodi_id' => $row['prodi_id'],
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
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
            'data' => array_filter($importData, fn($row) => $row['is_valid'])
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
   <Column ss:Width="120"/>
   <Column ss:Width="120"/>
   <Column ss:Width="180"/>
   <Column ss:Width="100"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">No. Registrasi</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Nama</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Jenis Kelamin</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Tahun Ajar</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Prodi</Data></Cell>
   </Row>';

        foreach ($data as $row) {
            $xml .= '
   <Row>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['no_registrasi'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['nama_calon'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['jenis_kelamin'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['tahun_ajar'], ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">' . htmlspecialchars($row['prodi_name'], ENT_XML1, 'UTF-8') . '</Data></Cell>
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
}
