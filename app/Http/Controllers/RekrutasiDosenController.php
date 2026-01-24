<?php

namespace App\Http\Controllers;

use App\Models\CalonDosen;
use App\Models\Prodi;
use Illuminate\Http\Request;
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
