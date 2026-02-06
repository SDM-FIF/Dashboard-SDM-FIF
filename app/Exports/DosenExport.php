<?php

namespace App\Exports;

use App\Models\Dosen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class DosenExport implements FromView, WithEvents, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return a view for export
     */
    public function view(): View
    {
        $query = Dosen::query()->with(['user', 'prodi.fakultas', 'kelompokKeahlian']);
        
        // Apply filters
        if (isset($this->filters['prodi_id']) && !empty($this->filters['prodi_id'])) {
            $query->where('prodi_id', $this->filters['prodi_id']);
        }
        
        if (isset($this->filters['jabatan']) && !empty($this->filters['jabatan'])) {
            $query->where('jabatan', $this->filters['jabatan']);
        }
        
        if (isset($this->filters['kelompok_keahlian_id']) && !empty($this->filters['kelompok_keahlian_id'])) {
            $query->where('kelompok_keahlian_id', $this->filters['kelompok_keahlian_id']);
        }
        
        if (isset($this->filters['status_pegawai']) && !empty($this->filters['status_pegawai'])) {
            $query->where('status_pegawai', $this->filters['status_pegawai']);
        }
        
        if (isset($this->filters['search']) && !empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('kode_dosen', 'like', '%' . $search . '%');
            });
        }
        
        $dosen = $query->orderBy('nama_lengkap')->get();
        
        return view('manajemen-dosen.export-excel', [
            'dosen' => $dosen
        ]);
    }

    /**
     * Register events for styling
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style header row
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C41E3A']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Set row height
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(25);

                // Format NIP column as text to prevent scientific notation
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $event->sheet->getDelegate()->setCellValueExplicit(
                        'A' . $row,
                        $event->sheet->getDelegate()->getCell('A' . $row)->getValue(),
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                    );
                }

                // Apply borders
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                
                $event->sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
