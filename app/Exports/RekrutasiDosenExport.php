<?php

namespace App\Exports;

use App\Models\CalonDosen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class RekrutasiDosenExport implements FromView, WithEvents, ShouldAutoSize
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
        $query = CalonDosen::query()->with(['prodi', 'tahunAjar']);
        
        // Apply filters
        if (isset($this->filters['prodi']) && !empty($this->filters['prodi'])) {
            $query->where('prodi_id', $this->filters['prodi']);
        }
        
        if (isset($this->filters['jenjang']) && !empty($this->filters['jenjang'])) {
            $query->whereHas('prodi', function($q) {
                $q->where('jenjang', $this->filters['jenjang']);
            });
        }
        
        if (isset($this->filters['tahun_ajar']) && !empty($this->filters['tahun_ajar'])) {
            $query->where('tahun_ajar_id', $this->filters['tahun_ajar']);
        }
        
        if (isset($this->filters['status']) && !empty($this->filters['status'])) {
            $query->where('status_penerimaan', $this->filters['status']);
        }
        
        $rekrutasi = $query->latest()->get();
        
        return view('rekrutasi-dosen.export-excel', [
            'rekrutasi' => $rekrutasi
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
                $event->sheet->getStyle('A1:F1')->applyFromArray([
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

                // Apply borders
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                
                $event->sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}