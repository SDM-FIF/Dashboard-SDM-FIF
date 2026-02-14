<?php

namespace App\Http\Controllers;

use App\Models\TenagaPendukungAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TenagaPendukungAkademikController extends Controller
{
    /**
     * Display a listing of TPA.
     */
    public function index(Request $request)
    {
        $query = TenagaPendukungAkademik::with('user');

        // Filter lokasi kerja
        if ($request->filled('lokasi_kerja')) {
            $query->where('lokasi_kerja', $request->lokasi_kerja);
        }

        // Filter status pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search nama
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
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
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
        }

        $tpa = $query->paginate(15);

        $filterData = [
            'lokasi_kerja' => TenagaPendukungAkademik::distinct()->pluck('lokasi_kerja')->filter(),
            'status_pegawai' => TenagaPendukungAkademik::distinct()->pluck('status_pegawai')->filter(),
        ];

        return view('manajemen-tpa.kelola-data', compact('tpa', 'filterData'));
    }

    public function kelolaData(Request $request)
    {
        $this->authorize('kelola-data-tpa.view');
        
        $query = TenagaPendukungAkademik::with('user');

        // Filter lokasi kerja
        if ($request->filled('lokasi_kerja')) {
            $query->where('lokasi_kerja', $request->lokasi_kerja);
        }

        // Filter status pegawai
        if ($request->filled('status_pegawai')) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Search nama
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('id', 'asc');
                break;
            case 'nama-az':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'nama-za':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('id', 'desc');
        }

        $tpa = $query->paginate(15);

        $filterData = [
            'lokasi_kerja' => TenagaPendukungAkademik::distinct()->pluck('lokasi_kerja')->filter(),
            'status_pegawai' => TenagaPendukungAkademik::distinct()->pluck('status_pegawai')->filter(),
        ];

        return view('manajemen-tpa.kelola-data', compact('tpa', 'filterData'));
    }

    /**
     * Form Import Data
     */
    public function importForm(Request $request)
    {
        $this->authorize('import-data-tpa.view');
        
        // Jika ada parameter reset, bersihkan session lalu redirect SATU KALI ke URL bersih
        if ($request->has('reset')) {
            session()->forget([
                'tpa_import_data',
                'import_result',
                'file_uploaded'
            ]);

            // Redirect ke route tanpa parameter apapun (URL bersih)
            return redirect()->route('manajemen-tpa.import-data');
        }

        // Ambil currentStep dari URL (default 1), tapi jangan di-redirect lagi!
        $currentStep = $request->get('step', 1);

        // Cek status upload dari session untuk pewarnaan progress bar
        $fileUploaded = session()->has('tpa_import_data');

        return view('manajemen-tpa.import-data', compact('currentStep', 'fileUploaded'));
    }

    /**
     * Download Template Import TPA (XML Stream)
     */
    public function downloadTemplate()
    {
        $filename = 'template-tpa.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () {
            echo $this->generateTemplateTPARaw();
        }, 200, $headers);
    }

    /**
     * XML Generator untuk Template TPA
     */
    private function generateTemplateTPARaw()
    {
        // Menggunakan style XML Spreadsheet 2003 agar bisa diberi warna
        return '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:xml-opensheets"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="Header">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>
   <Interior ss:Color="#C41E3A" ss:Pattern="Solid"/> </Style>
  <Style ss:ID="DataCell">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Template Import TPA">
  <Table>
   <Column ss:Width="180"/> <Column ss:Width="120"/> <Column ss:Width="130"/> <Column ss:Width="120"/> <Column ss:Width="150"/> <Column ss:Width="140"/> <Column ss:Width="120"/> <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">NAMA LENGKAP</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">NIP</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">PANGKAT GOLONGAN</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">STATUS PEGAWAI</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">LOKASI KERJA</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">PENDIDIKAN TERAKHIR</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">USERNAME</Data></Cell>
   </Row>
   
   <Row>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="DataCell"><Data ss:Type="String"></Data></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>';
    }


    /**
     * Process Upload & Preview Data
     */
    public function importProcess(Request $request)
    {
        $this->authorize('import-data-tpa.view');
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $rawRows = Excel::toArray([], $file)[0];

            // Hapus header (baris pertama)
            array_shift($rawRows);

            $importData = [];
            foreach ($rawRows as $row) {
                if (empty($row[0]))
                    continue; // Lewati jika baris kosong

                $importData[] = [
                    'nama_lengkap' => $row[0] ?? '', // Kolom A
                    'nip' => $row[1] ?? '', // Kolom B
                    'pangkat_golongan' => $row[2] ?? '', // Kolom C
                    'status_pegawai' => $row[3] ?? '', // Kolom D
                    'lokasi_kerja' => $row[4] ?? '', // Kolom E
                    'pendidikan_terakhir' => $row[5] ?? '', // Kolom F
                    'username' => $row[6] ?? '', // Kolom G
                    'is_duplicate' => TenagaPendukungAkademik::where('nip', $row[1])->exists()
                ];
            }

            session(['tpa_import_data' => $importData]);

            return view('manajemen-tpa.import-data', [
                'previewData' => $importData,
                'step' => 2
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membaca file: ' . $e->getMessage()]);
        }
    }

    /**
     * Store Previewed Data to Database
     */
    public function storeImport(Request $request)
    {
        $this->authorize('import-data-tpa.view');
        
        $data = session('tpa_import_data');

        if (!$data) {
            return redirect()->route('manajemen-tpa.import')->withErrors(['error' => 'Session habis, silakan upload ulang.']);
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($data as $item) {
            try {
                DB::transaction(function () use ($item, &$successCount) {
                    // 1. Cek NIP agar tidak duplikat
                    if (TenagaPendukungAkademik::where('nip', $item['nip'])->exists()) {
                        throw new \Exception("NIP Duplikat");
                    }

                    // 2. Buat User Account
                    $user = User::create([
                        'role_id' => 1, // Sesuaikan ID Role TPA
                        'nama_lengkap' => $item['nama_lengkap'],
                        'username' => $item['username'],
                        'password' => Hash::make('Password123'), // Default password
                    ]);

                    // 3. Buat Data TPA
                    TenagaPendukungAkademik::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $item['nama_lengkap'],
                        'nip' => $item['nip'],
                        'pangkat_golongan' => $item['pangkat_golongan'],
                        'status_pegawai' => $item['status_pegawai'],
                        'lokasi_kerja' => $item['lokasi_kerja'],
                        'pendidikan_terakhir' => $item['pendidikan_terakhir'],
                    ]);

                    $successCount++;
                });
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Import Gagal untuk NIP " . $item['nip'] . ": " . $e->getMessage());
            }
        }

        session()->forget('tpa_import_data');

        return view('manajemen-tpa.import-result', [
            'result' => [
                'success' => $successCount,
                'failed' => $failedCount
            ]
        ]);
    }

    /**
     * Halaman Laporan TPA
     */
    public function laporan()
    {
        $statistik = [
            'total' => TenagaPendukungAkademik::count(),
            'per_status' => TenagaPendukungAkademik::selectRaw('status_pegawai, COUNT(*) as total')
                ->groupBy('status_pegawai')
                ->pluck('total', 'status_pegawai'),
            'per_lokasi' => TenagaPendukungAkademik::selectRaw('lokasi_kerja, COUNT(*) as total')
                ->groupBy('lokasi_kerja')
                ->pluck('total', 'lokasi_kerja'),
        ];

        return view('manajemen-tpa.laporan', compact('statistik'));
    }

    /**
     * Show the form for creating a new TPA.
     */
    public function create()
    {
        $this->authorize('kelola-data-tpa.create');
        
        return view('manajemen-tpa.tambah-data');
    }

    /**
     * Store a newly created TPA in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('kelola-data-tpa.create');
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:tenaga_pendukung_akademik,nip',
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_pegawai' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'username' => 'required|string|max:100|unique:user,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create user
            $user = User::create([
                'role_id' => 1, // ⬅️ sesuaikan role TPA jika ada role khusus
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create TPA
            TenagaPendukungAkademik::create([
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'pangkat_golongan' => $validated['pangkat_golongan'],
                'status_pegawai' => $validated['status_pegawai'],
                'lokasi_kerja' => $validated['lokasi_kerja'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            ]);

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified TPA.
     */
    public function show(Request $request, TenagaPendukungAkademik $tpa)
    {
        $this->authorize('kelola-data-tpa.detail');
        
        // load relasi user
        $tpa->load('user');

        // Ambil semua TPA untuk list di bawah detail (dengan filter & sort)
        $query = TenagaPendukungAkademik::with('user');

        // Filter status pegawai jika ada
        if ($request->filled('filter_status')) {
            $query->where('status_pegawai', $request->filter_status);
        }

        // Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
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
            $query->orderBy('id', 'desc');
        }

        $allTpa = $query->paginate(10)->appends($request->query());

        return view('manajemen-tpa.detail-data', compact('tpa', 'allTpa'));
    }


    /**
     * Show the form for editing the specified TPA.
     */
    public function edit(TenagaPendukungAkademik $tpa)
    {
        $this->authorize('kelola-data-tpa.edit');
        
        $tpa->load('user');
        return view('manajemen-tpa.edit-data', compact('tpa'));
    }

    /**
     * Update the specified TPA in storage.
     */
    public function update(Request $request, TenagaPendukungAkademik $tpa)
    {
        $this->authorize('kelola-data-tpa.edit');
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:tenaga_pendukung_akademik,nip,' . $tpa->id,
            'pangkat_golongan' => 'nullable|string|max:50',
            'status_pegawai' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|max:50',
            'username' => 'required|string|max:100|unique:user,username,' . $tpa->user_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Update user
            $userData = [
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $tpa->user->update($userData);

            // Update TPA
            $tpa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'pangkat_golongan' => $validated['pangkat_golongan'],
                'status_pegawai' => $validated['status_pegawai'],
                'lokasi_kerja' => $validated['lokasi_kerja'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            ]);

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified TPA from storage.
     */
    public function destroy(TenagaPendukungAkademik $tpa)
    {
        $this->authorize('kelola-data-tpa.delete');
        
        try {
            $user = $tpa->user;
            $tpa->delete();
            $user->delete();

            return redirect()
                ->route('manajemen-tpa.kelola-data')
                ->with('success', 'Data TPA berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman laporan TPA
     */

}
