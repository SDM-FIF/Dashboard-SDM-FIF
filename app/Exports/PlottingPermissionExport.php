<?php

namespace App\Exports;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PlottingPermissionExport implements FromView, WithEvents, ShouldAutoSize
{
    protected $roleId;
    protected $role;
    protected $permissionData;

    public function __construct($roleId)
    {
        $this->roleId = $roleId;
        $this->role = Role::findOrFail($roleId);
        $this->preparePermissionData();
    }

    /**
     * Prepare permission data
     */
    protected function preparePermissionData()
    {
        $moduleGroups = [
            'Dashboard' => [
                'dashboard-sdm' => 'Dashboard SDM',
                'dashboard-dosen' => 'Dashboard Dosen',
                'dashboard-tpa' => 'Dashboard TPA',
                'dashboard-kompetisi' => 'Dashboard Kompetisi',
            ],
            'Manajemen Dosen' => [
                'kelola-data-dosen' => 'Kelola Data',
                'import-data-dosen' => 'Import Data',
                'laporan-data-dosen' => 'Laporan Dosen',
            ],
            'Manajemen TPA' => [
                'kelola-data-tpa' => 'Kelola Data',
                'import-data-tpa' => 'Import Data',
            ],
            'Rekrutasi Dosen' => [
                'rekrutasi-data-dosen' => 'Data Rekrutasi Dosen',
                'import-rekrutasi-dosen' => 'Import Rekrutasi Dosen',
                'jadwal-pengujian' => 'Jadwal Pengujian Dosen',
                'penilaian-dosen' => 'Penilaian Calon Dosen',
                'berita-acara' => 'Berita Acara',
                'hasil-pengujian' => 'Hasil Pengujian',
            ],
            'Manajemen Mahasiswa' => [
                'kelola-data-mahasiswa' => 'Kelola Data',
                'import-data-mahasiswa' => 'Import Data',
            ],
            'Master Data' => [
                'master-data-fakultas' => 'Data Fakultas',
                'master-data-prodi' => 'Data Program Studi',
                'master-data-kompetisi' => 'Data Kompetisi',
                'master-data-tahun-ajar' => 'Data Tahun Ajaran',
                'master-data-kelompok-keahlian' => 'Data Kelompok Keahlian',
            ],
            'Pengaturan' => [
                'konfigurasi-sistem' => 'Konfigurasi Sistem',
                'user-management' => 'User Management',
            ]
        ];

        $this->permissionData = [];
        foreach ($moduleGroups as $parentModule => $subModules) {
            foreach ($subModules as $key => $label) {
                // Define permission types based on module
                if ($key === 'penilaian-dosen' || $key === 'berita-acara') {
                    $permissionTypes = ['all', 'access', 'submit'];
                } elseif ($key === 'hasil-pengujian') {
                    $permissionTypes = ['all', 'view'];
                } else {
                    $permissionTypes = ['all', 'view', 'detail', 'create', 'edit', 'delete'];
                }

                $permissions = [];
                foreach ($permissionTypes as $type) {
                    $permission = Permission::where('name', "{$key}.{$type}")->first();
                    if ($permission) {
                        $hasPermission = $this->role->hasPermissionTo($permission->name);
                        $permissions[$type] = $hasPermission ? 'Ya' : 'Tidak';
                    }
                }

                $this->permissionData[] = [
                    'parent_module' => $parentModule,
                    'sub_module' => $label,
                    'permissions' => $permissions
                ];
            }
        }
    }

    /**
     * Return a view for export
     */
    public function view(): View
    {
        return view('pengaturan.plotting-export-excel', [
            'role' => $this->role,
            'permissionData' => $this->permissionData
        ]);
    }

    /**
     * Register events for styling
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style title rows (A1:H2)
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'PLOTTING PERMISSION HAK AKSES');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
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
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);

                // Role info row
                $event->sheet->mergeCells('A2:H2');
                $event->sheet->setCellValue('A2', 'Role: ' . $this->role->name);
                $event->sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(20);

                // Style header row (row 4)
                $event->sheet->getStyle('A4:H4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11
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
                $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(25);

                // Apply borders to all data
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getStyle('A4:H' . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Center align permission columns
                $event->sheet->getStyle('C5:H' . $highestRow)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Set column widths
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
            },
        ];
    }
}
