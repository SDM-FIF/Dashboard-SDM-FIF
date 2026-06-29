<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPengujian extends Model
{
    protected $table = 'hasil_pengujian';
    
    protected $fillable = [
        'jadwal_pengujian_id',
        'calon_dosen_id',
        'dosen_id',
        'penilaian_detail_id',
        'rekomendasi_akhir',
    ];

    // Relasi
    public function jadwalPengujian()
    {
        return $this->belongsTo(JadwalPengujian::class);
    }

    public function calonDosen()
    {
        return $this->belongsTo(CalonDosen::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function penilaianDetail()
    {
        return $this->belongsTo(PenilaianDetail::class);
    }
}
