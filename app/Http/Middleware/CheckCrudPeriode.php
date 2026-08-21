<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PengaturanPeriodeCrud;
use Carbon\Carbon;

class CheckCrudPeriode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $fitur
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $fitur)
    {
        // Super Admin always bypasses all CRUD time restrictions
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
            return $next($request);
        }

        $setting = PengaturanPeriodeCrud::where('fitur', $fitur)->first();
        if ($setting && $setting->mode === 'rentang_tanggal') {
            $now = Carbon::now()->startOfDay();
            $mulai = $setting->tanggal_mulai ? Carbon::parse($setting->tanggal_mulai)->startOfDay() : null;
            $selesai = $setting->tanggal_selesai ? Carbon::parse($setting->tanggal_selesai)->endOfDay() : null;

            $isAllowed = true;
            if ($mulai && $now->lt($mulai)) {
                $isAllowed = false;
            }
            if ($selesai && $now->gt($selesai)) {
                $isAllowed = false;
            }

            if (!$isAllowed) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aksi dibatasi. Periode akses pengisian data fitur ini belum dimulai atau sudah berakhir.'
                    ], 403);
                }

                $fiturLabel = match ($fitur) {
                    'dosen' => 'Dosen',
                    'tpa' => 'Tenaga Pendukung Akademik (TPA)',
                    'rekrutasi' => 'Rekrutasi Dosen',
                    'mahasiswa' => 'Mahasiswa & Kompetisi',
                    'master' => 'Master Data',
                    'surat' => 'Surat Tugas & SK',
                    default => strtoupper($fitur)
                };

                $msg = 'Aksi dibatasi. Pengisian/perubahan data ' . $fiturLabel . ' hanya diperbolehkan pada tanggal ' . 
                       ($mulai ? $mulai->translatedFormat('d M Y') : 'awal') . ' s/d ' . 
                       ($selesai ? $selesai->translatedFormat('d M Y') : 'selesai') . '.';

                return redirect()->back()->with('error', $msg);
            }
        }

        return $next($request);
    }
}
