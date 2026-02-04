<?php

namespace App\Exports;

use App\Models\PenilaianDetail;
use App\Models\JadwalPengujian;
use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PenilaianCalonDosenExport implements FromCollection, WithHeadings, WithDrawings, WithEvents, WithColumnWidths, WithStyles
{
    protected $penilaianDetail;
    protected $jadwal;
    protected $calonDosen;
    protected $dosenPenguji;
    protected $qrCodePath = null;

    public function __construct($penilaianId)
    {
        $this->penilaianDetail = PenilaianDetail::with(['dosen', 'user'])->findOrFail($penilaianId);
        $this->jadwal = JadwalPengujian::with(['calonDosen.prodi', 'tahunAjar'])->findOrFail($this->penilaianDetail->jadwal_pengujian_id);
        $this->calonDosen = $this->jadwal->calonDosen;
        
        // Get dosen penguji from user relationship
        $dosenPenguji = Dosen::where('user_id', $this->penilaianDetail->user_id)->first();
        $this->dosenPenguji = $dosenPenguji ?? $this->penilaianDetail->dosen;
        
        // Generate QR Code immediately in constructor
        $this->generateQrCode();
    }
    
    protected function generateQrCode()
    {
        if (!$this->dosenPenguji || !$this->dosenPenguji->nip) {
            return;
        }
        
        try {
            // JSON data for QR Code
            $qrData = json_encode([
                'nip' => $this->dosenPenguji->nip,
                'nama' => ($this->dosenPenguji->front_title ?? '') . ' ' . 
                          ($this->dosenPenguji->nama_lengkap ?? '') . ', ' . 
                          ($this->dosenPenguji->back_title ?? '')
            ], JSON_UNESCAPED_UNICODE);
            
            // Generate QR Code (kotak/square, mudah di-scan HP)
            // Using chillerlan/php-qrcode with GD support
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
                'imageBase64' => false,
            ]);
            
            $qrcode = new QRCode($options);
            $qrCodeImage = $qrcode->render($qrData);
            
            // Save to PERMANENT directory (public/images/qrcodes/) - no deletion needed
            // Reuse same file for same dosen
            $qrCodeDir = public_path('images' . DIRECTORY_SEPARATOR . 'qrcodes');
            if (!file_exists($qrCodeDir)) {
                mkdir($qrCodeDir, 0755, true);
            }
            
            $filename = 'qrcode_dosen_' . $this->dosenPenguji->id . '.png';
            $this->qrCodePath = $qrCodeDir . DIRECTORY_SEPARATOR . $filename;
            
            file_put_contents($this->qrCodePath, $qrCodeImage);
            \Log::info('QR Code saved permanently', ['path' => $this->qrCodePath, 'size' => filesize($this->qrCodePath)]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR Code: ' . $e->getMessage());
        }
    }

    public function collection()
    {
        $p = $this->penilaianDetail;
        $c = $this->calonDosen;
        $j = $this->jadwal;
        
        return collect([
            // Header rows
            ['FORM PENILAIAN CALON DOSEN', '', '', ''],
            ['TELKOM UNIVERSITY', '', '', ''],
            ['', '', '', ''],
            ['Nama Calon Dosen', ': ' . $c->nama, '', ''],
            ['Program Studi', ': ' . ($c->prodi->nama_prodi ?? '-'), '', ''],
            ['Tahun Ajaran', ': ' . ($j->tahunAjar->label ?? '-'), '', ''],
            ['Tanggal Pengujian', ': ' . \Carbon\Carbon::parse($j->tanggal_pengujian)->format('d F Y'), '', ''],
            ['', '', '', ''],
            
            // Column headers
            ['No', 'Kriteria Penilaian', 'Nilai', 'Keterangan'],
            
            // Section A
            ['A. KUALIFIKASI (40%)', '', '', ''],
            ['1', 'Jalur Lamaran / Pendidikan = ' . ($c->jalur_lamaran ?? '-'), number_format($p->nilai_jalur_lamaran, 2), ''],
            ['2', 'Jabatan Fungsional Akademik (JFA) = ' . ($c->jabatan_fungsional_akademik ?? 'NJFA'), number_format($p->nilai_jfa, 2), ''],
            ['3', 'H-Index = ' . ($c->h_index ?? 0), number_format($p->nilai_h_index, 2), 'Rata-rata A = ' . number_format($p->rata_a, 2)],
            
            // Section B
            ['B. MICRO TEACHING (20%)', '', '', ''],
            ['1', 'Penguasaan materi & audiens', number_format($p->nilai_pma, 2), ''],
            ['2', 'Sistematika (kemudahan dipahami)', number_format($p->nilai_sistematika, 2), ''],
            ['3', 'Kejelasan suara & tulisan', number_format($p->nilai_kst, 2), 'Rata-rata B = ' . number_format($p->rata_b, 2)],
            
            // Section C
            ['C. WAWANCARA (40%)', '', '', ''],
            ['1', 'Motivasi', number_format($p->nilai_motivasi, 2), ''],
            ['2', 'Kemampuan mengajar', number_format($p->nilai_kmp_mengajar, 2), ''],
            ['3', 'Kemampuan mengembangkan kurikulum Pengajaran', number_format($p->nilai_kmp_mkp, 2), ''],
            ['4', 'Kemampuan penelitian & publikasi', number_format($p->nilai_kmp_pp, 2), ''],
            ['5', 'Kemampuan Abdimas', number_format($p->nilai_kmp_abdimas, 2), ''],
            ['6', 'Kemampuan Bekerjasama dengan tim', number_format($p->nilai_kmp_bdt, 2), ''],
            ['7', 'Keahlian lainnya', number_format($p->nilai_keahlian_lainnya, 2), ''],
            ['8', 'Komitmen waktu dan kesediaan melakukan hal diluar tugas pokok', number_format($p->nilai_kmt_wkm, 2), 'Rata-rata C = ' . number_format($p->rata_c, 2)],
            
            // Total
            ['TOTAL NILAI (Rata-rata Berbobot)', '', number_format($p->rata_nilai, 2), $p->keterangan_berbobot],
            ['Kesiapan bergabung segera?', '', $p->kesiapan ? 'YA' : 'TIDAK/PIKIR-PIKIR', ''],
            ['Bersedia dengan standard gaji?', '', $p->kesediaan ? 'YA' : 'TIDAK/PIKIR-PIKIR', ''],
            ['', '', '', ''],
            ['Catatan/Komentar:', '', '', ''],
            [$p->catatan_penilai ?? '', '', '', ''],
            ['', '', '', ''],
            ['', '', '', ''],
            
            // Section Pengesahan
            ['', '', '', 'Bandung, ' . \Carbon\Carbon::parse($p->created_at)->format('d F Y')],
            ['', '', '', 'PENILAI'],
            ['', '', '', ''],
            ['', '', '', ''],
            ['', '', '', ''],
            ['', '', '', ''],
            ['', '', '', ''],
        ]);
    }

    public function headings(): array
    {
        return []; // Headings already in collection
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 60,
            'C' => 18,
            'D' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for headers
        $sheet->mergeCells('A1:D1'); // Title
        $sheet->mergeCells('A2:D2'); // University
        
        // Center align and bold for headers
        $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        
        // Info section - wrap text for better display
        $sheet->getStyle('A4:D7')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A4:A7')->getFont()->setBold(true);
        
        // Center align column headers (row 9)
        $sheet->getStyle('A9:D9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A9:D9')->getFont()->setBold(true);
        $sheet->getStyle('A9:D9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F0F0');
        
        // Section headers background
        $sheet->mergeCells('A10:D10'); // Section A
        $sheet->mergeCells('A14:D14'); // Section B
        $sheet->mergeCells('A18:D18'); // Section C
        
        $sheet->getStyle('A10:D10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9EAD3');
        $sheet->getStyle('A10:D10')->getFont()->setBold(true);
        $sheet->getStyle('A14:D14')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9EAD3');
        $sheet->getStyle('A14:D14')->getFont()->setBold(true);
        $sheet->getStyle('A18:D18')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9EAD3');
        $sheet->getStyle('A18:D18')->getFont()->setBold(true);
        
        // Total row background
        $sheet->mergeCells('A27:B27');
        $sheet->getStyle('A27:D27')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCFE2F3');
        $sheet->getStyle('A27:D27')->getFont()->setBold(true);
        $sheet->getStyle('A27:D27')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Merge kesiapan & kesediaan
        $sheet->mergeCells('A28:B28');
        $sheet->mergeCells('A29:B29');
        $sheet->getStyle('A28:B28')->getFont()->setBold(true);
        $sheet->getStyle('A29:B29')->getFont()->setBold(true);
        $sheet->getStyle('A28:D29')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Catatan section - merge and add border
        $sheet->mergeCells('A31:D31'); // Label "Catatan/Komentar"
        $sheet->mergeCells('A32:D32'); // Content
        $sheet->getStyle('A31')->getFont()->setBold(true);
        $sheet->getStyle('A32')->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
        
        // Add border to catatan box
        $sheet->getStyle('A31:D32')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A31:D32')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9E7');
        
        // Set row height for catatan to make it spacious
        $sheet->getRowDimension(32)->setRowHeight(60);
        
        // Section Pengesahan styling
        $sheet->getStyle('D34:D40')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D35')->getFont()->setBold(true)->setSize(12); // PENILAI
        $sheet->getStyle('D39')->getFont()->setBold(true); // Nama dosen
        $sheet->getStyle('D40')->getFont()->setSize(10); // NIP
        
        return [];
    }

    public function drawings()
    {
        $drawings = [];
        
        // Logo Telkom University
        $logoPath = public_path('images' . DIRECTORY_SEPARATOR . 'LogoTelkom.png');
        if (file_exists($logoPath)) {
            try {
                $logo = new Drawing();
                $logo->setName('Logo Telkom');
                $logo->setDescription('Logo Telkom University');
                $logo->setPath($logoPath);
                $logo->setHeight(50);
                $logo->setCoordinates('A1');
                $logo->setOffsetX(10);
                $logo->setOffsetY(10);
                $drawings[] = $logo;
                \Log::info('Logo added successfully');
            } catch (\Exception $e) {
                \Log::error('Failed to add logo: ' . $e->getMessage());
            }
        }
        
        // QR Code (generated in constructor, saved permanently)
        if ($this->qrCodePath && file_exists($this->qrCodePath)) {
            try {
                $qrCode = new Drawing();
                $qrCode->setName('QR Code');
                $qrCode->setDescription('QR Code NIP Dosen');
                $qrCode->setPath($this->qrCodePath);
                $qrCode->setHeight(80);
                $qrCode->setCoordinates('D37');
                $qrCode->setOffsetX(75);
                $qrCode->setOffsetY(10);
                $drawings[] = $qrCode;
                \Log::info('QR Code added successfully', ['path' => $this->qrCodePath]);
            } catch (\Exception $e) {
                \Log::error('Failed to add QR Code: ' . $e->getMessage());
            }
        }
        
        \Log::info('Total drawings prepared', ['count' => count($drawings)]);
        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Apply row heights
                $sheet = $event->sheet->getDelegate();
                
                // Header rows
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(20);
                
                // Info section
                for ($i = 4; $i <= 7; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);
                }
                
                // Data rows
                for ($i = 9; $i <= 26; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
                
                // Total & decision rows
                $sheet->getRowDimension(27)->setRowHeight(25);
                $sheet->getRowDimension(28)->setRowHeight(20);
                $sheet->getRowDimension(29)->setRowHeight(20);
                
                // Pengesahan section
                for ($i = 34; $i <= 40; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
                
                // No cleanup needed - QR Code files are permanent
            },
        ];
    }
    
    public function __destruct()
    {
        // Don't delete here - let shutdown function handle it
        // This was causing the file to be deleted too early
    }
}
