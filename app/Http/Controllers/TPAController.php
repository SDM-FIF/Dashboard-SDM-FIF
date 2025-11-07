<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TpaController extends Controller
{
    public function dashboard()
    {
        // 🔹 1. Jumlah TPA per Status Pegawai
        $statusTpa = DB::table('tenaga_pendukung_akademik')
            ->select('status_pegawai', DB::raw('count(*) as total'))
            ->groupBy('status_pegawai')
            ->pluck('total', 'status_pegawai');

        // 🔹 2. Jumlah TPA per Lokasi Kerja
        $lokasiTpa = DB::table('tenaga_pendukung_akademik')
            ->select('lokasi_kerja', DB::raw('count(*) as total'))
            ->groupBy('lokasi_kerja')
            ->pluck('total', 'lokasi_kerja');

        // 🔹 3. Jumlah TPA per Pendidikan Terakhir
        $pendidikanTpa = DB::table('tenaga_pendukung_akademik')
            ->select('pendidikan_terakhir', DB::raw('count(*) as total'))
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir');

        // 🔹 4. Jumlah TPA per Pangkat/Golongan
        $pangkatTpa = DB::table('tenaga_pendukung_akademik')
            ->select('pangkat_golongan', DB::raw('count(*) as total'))
            ->groupBy('pangkat_golongan')
            ->pluck('total', 'pangkat_golongan');

        return view('dashboard-tpa', [
            'statusTpa' => $statusTpa,
            'lokasiTpa' => $lokasiTpa,
            'pendidikanTpa' => $pendidikanTpa,
            'pangkatTpa' => $pangkatTpa,
        ]);
    }
}
