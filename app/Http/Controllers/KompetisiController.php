<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKompetisi;
use Illuminate\Support\Facades\DB;

class KompetisiController extends Controller
{
    public function index()
    {
        // 🔹 1. Jumlah Juara
        $jumlahJuara = MahasiswaKompetisi::select('juara', DB::raw('count(*) as total'))
            ->whereIn('juara', ['Juara 1', 'Juara 2', 'Juara 3'])
            ->groupBy('juara')
            ->pluck('total', 'juara');

        // 🔹 2. Jumlah Mahasiswa Akademik vs Non-Akademik
        $jumlahMahasiswa = MahasiswaKompetisi::select('jenis', DB::raw('count(*) as total'))
            ->whereIn('jenis', ['AKADEMIK', 'NON-AKADEMIK'])
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        // 🔹 3. Status Mahasiswa
        $statusMahasiswa = Mahasiswa::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['AKTIF', 'CUTI', 'TIDAK AKTIF'])
            ->groupBy('status')
            ->pluck('total', 'status');

        // 🔹 4. Jumlah Kompetisi per Tingkat
        $jumlahKompetisi = Kompetisi::select('tingkat_kompetisi', DB::raw('count(*) as total'))
            ->groupBy('tingkat_kompetisi')
            ->pluck('total', 'tingkat_kompetisi');

        // 🔹 5. Jumlah Kompetisi per Tingkat (versi 2)
        $jumlahKompetisi2 = DB::table('kompetisi')
            ->select('tingkat_kompetisi', DB::raw('count(*) as total'))
            ->groupBy('tingkat_kompetisi')
            ->pluck('total', 'tingkat_kompetisi');


        return view('dashboard-kompetisi', [
            'jumlahJuara' => $jumlahJuara,
            'jumlahMahasiswa' => $jumlahMahasiswa,
            'statusMahasiswa' => $statusMahasiswa,
            'jumlahKompetisi' => $jumlahKompetisi,
            'jumlahKompetisi2' => $jumlahKompetisi2,
        ]);
    }
}
