<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    protected $table = 'penilaian_detail';

    protected $fillable = [
        'dosen_id',
        'calon_dosen_id',
        'jadwal_pengujian_id',
        'nilai_jalur_lamaran',
        'nilai_h_index',
        'nilai_jfa',
        'nilai_pma',
        'nilai_sistematika',
        'nilai_kst',
        'nilai_motivasi',
        'nilai_kmp_mengajar',
        'nilai_kmp_mkp',
        'nilai_kmp_pp',
        'nilai_kmp_abdimas',
        'nilai_kmp_bdt',
        'nilai_keahlian_lainnya',
        'nilai_kmt_wkm',
        'rata_a',
        'rata_b',
        'rata_c',
        'rata_nilai',
        'rata_akhir',
        'keterangan_berbobot',
        'kesiapan',
        'kesediaan',
        'catatan_penilai',
    ];

    protected $casts = [
        'nilai_jalur_lamaran' => 'decimal:2',
        'nilai_h_index' => 'decimal:2',
        'nilai_jfa' => 'decimal:2',
        'nilai_pma' => 'decimal:2',
        'nilai_sistematika' => 'decimal:2',
        'nilai_kst' => 'decimal:2',
        'nilai_motivasi' => 'decimal:2',
        'nilai_kmp_mengajar' => 'decimal:2',
        'nilai_kmp_mkp' => 'decimal:2',
        'nilai_kmp_pp' => 'decimal:2',
        'nilai_kmp_abdimas' => 'decimal:2',
        'nilai_kmp_bdt' => 'decimal:2',
        'nilai_keahlian_lainnya' => 'decimal:2',
        'nilai_kmt_wkm' => 'decimal:2',
        'rata_a' => 'decimal:2',
        'rata_b' => 'decimal:2',
        'rata_c' => 'decimal:2',
        'rata_nilai' => 'decimal:2',
        'rata_akhir' => 'decimal:2',
        'kesiapan' => 'boolean',
        'kesediaan' => 'boolean',
    ];

    // Relasi
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function calonDosen()
    {
        return $this->belongsTo(CalonDosen::class);
    }

    public function jadwalPengujian()
    {
        return $this->belongsTo(JadwalPengujian::class);
    }

    public function hasilPengujian()
    {
        return $this->hasOne(HasilPengujian::class);
    }
}
