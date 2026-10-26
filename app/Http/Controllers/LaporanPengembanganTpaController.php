<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanPengembanganTpa;
use App\Models\LaporanAkreditasiTpa;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LaporanPengembanganTpaController extends Controller
{
    public function index()
    {
        $kegiatan = KegiatanPengembanganTpa::orderBy('tahun')
            ->orderBy('kegiatan')
            ->get();

        $ringkasan = LaporanAkreditasiTpa::pluck('value', 'key')
            ->toArray();

        return view('laporan-pengembangan-tpa.index', compact(
            'kegiatan',
            'ringkasan'
        ));
    }

    public function storeKegiatan(Request $request)
    {
        $data = $request->validate([
            'kegiatan' => 'required|in:Pelatihan,Sertifikasi,Studi Lanjut',
            'tahun' => 'required|integer|min:2000|max:2100',
            'jumlah' => 'required|integer|min:0',
        ]);

        KegiatanPengembanganTpa::updateOrCreate(
            [
                'kegiatan' => $data['kegiatan'],
                'tahun' => $data['tahun'],
            ],
            [
                'jumlah' => $data['jumlah'],
            ]
        );

        return redirect()
            ->route('laporan-pengembangan-tpa.index')
            ->with('success', 'Data kegiatan berhasil disimpan.');
    }

    public function storeRingkasan(Request $request)
    {
        $data = $request->validate([
            'jumlah_mengikuti' => 'required|integer|min:0|lte:jumlah_tenaga',
            'jumlah_tenaga' => 'required|integer|min:0',
        ], [
            'jumlah_mengikuti.lte' =>
                'Jumlah peserta tidak boleh melebihi jumlah seluruh tenaga kependidikan.',
        ]);

        LaporanAkreditasiTpa::updateOrCreate(
            ['key' => 'jumlah_mengikuti'],
            ['value' => $data['jumlah_mengikuti']]
        );

        LaporanAkreditasiTpa::updateOrCreate(
            ['key' => 'jumlah_tenaga'],
            ['value' => $data['jumlah_tenaga']]
        );

        return redirect()
            ->route('laporan-pengembangan-tpa.index')
            ->with('success', 'Ringkasan laporan berhasil disimpan.');
    }

    
public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ]);

    try {
        $spreadsheet = IOFactory::load(
            $request->file('file')->getRealPath()
        );

        $sheet = $spreadsheet->getSheetByName(
            'Laporan Pengembangan TPA'
        ) ?? $spreadsheet->getActiveSheet();

        // Baca ringkasan dari template Excel
        $jumlahMengikuti = $sheet->getCell('B2')->getCalculatedValue();
        $jumlahTenaga = $sheet->getCell('B3')->getCalculatedValue();

        $validJumlah = function ($value) {
            return is_numeric($value)
                && (float) $value >= 0
                && (float) $value === (float) (int) $value;
        };

        if (
            !$validJumlah($jumlahMengikuti) ||
            !$validJumlah($jumlahTenaga)
        ) {
            return back()->withErrors([
                'file' => 'Jumlah pada ringkasan Excel harus berupa bilangan bulat nonnegatif.',
            ]);
        }

        if ((int) $jumlahMengikuti > (int) $jumlahTenaga) {
            return back()->withErrors([
                'file' => 'Jumlah yang mengikuti pengembangan kompetensi tidak boleh melebihi jumlah seluruh tenaga kependidikan.',
            ]);
        }

        // Tahun berada di F3 sampai J3
        $kolomTahun = ['F', 'G', 'H', 'I', 'J'];
        $years = [];

        foreach ($kolomTahun as $kolom) {
            $tahun = $sheet->getCell($kolom . '3')->getCalculatedValue();

            if (
                !$validJumlah($tahun) ||
                (int) $tahun < 2000 ||
                (int) $tahun > 2100
            ) {
                return back()->withErrors([
                    'file' => "Tahun pada kolom {$kolom}3 tidak valid.",
                ]);
            }

            $tahun = (int) $tahun;

            if (in_array($tahun, $years, true)) {
                return back()->withErrors([
                    'file' => "Tahun {$tahun} tercantum lebih dari sekali.",
                ]);
            }

            $years[$kolom] = $tahun;
        }

        // Nama kegiatan berada di E4 sampai E6
        $expectedActivities = [
            4 => 'Pelatihan',
            5 => 'Sertifikasi',
            6 => 'Studi Lanjut',
        ];

        $records = [];

        foreach ($expectedActivities as $row => $activity) {
            $namaExcel = trim(
                (string) $sheet->getCell("E{$row}")->getCalculatedValue()
            );

            if ($namaExcel !== $activity) {
                return back()->withErrors([
                    'file' => "Format nama kegiatan pada baris {$row} tidak sesuai template.",
                ]);
            }

            foreach ($kolomTahun as $kolom) {
                $jumlah = $sheet->getCell(
                    $kolom . $row
                )->getCalculatedValue();

                if (!$validJumlah($jumlah)) {
                    return back()->withErrors([
                        'file' => "Jumlah kegiatan {$activity} tahun {$years[$kolom]} harus berupa bilangan bulat nonnegatif.",
                    ]);
                }

                $records[] = [
                    'kegiatan' => $activity,
                    'tahun' => $years[$kolom],
                    'jumlah' => (int) $jumlah,
                ];
            }
        }

        DB::transaction(function () use (
            $jumlahMengikuti,
            $jumlahTenaga,
            $records
        ) {
            LaporanAkreditasiTpa::updateOrCreate(
                ['key' => 'jumlah_mengikuti'],
                ['value' => (string) $jumlahMengikuti]
            );

            LaporanAkreditasiTpa::updateOrCreate(
                ['key' => 'jumlah_tenaga'],
                ['value' => (string) $jumlahTenaga]
            );

            foreach ($records as $record) {
                KegiatanPengembanganTpa::updateOrCreate(
                    [
                        'kegiatan' => $record['kegiatan'],
                        'tahun' => $record['tahun'],
                    ],
                    ['jumlah' => $record['jumlah']]
                );
            }
        });

        return redirect()
            ->route('laporan-pengembangan-tpa.index')
            ->with('success', 'Data laporan berhasil diimpor dari Excel.');

    } catch (\Throwable $e) {
        report($e);

        return back()->withErrors([
            'file' => 'File Excel tidak dapat dibaca. Pastikan menggunakan template yang sesuai.',
        ]);
    }
}

}

