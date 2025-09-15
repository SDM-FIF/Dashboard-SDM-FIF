<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPengujian extends Model
{
    protected $table = 'hasil_pengujian';
    
    protected $fillable = [
        'jadwal_pengujian_id',
        'total_nilai',
        'berita_acara',
    ];

    // Relasi
    public function jadwalPengujian()
    {
        return $this->belongsTo(JadwalPengujian::class);
    }

    public function penilaianDetail()
    {
        return $this->hasMany(PenilaianDetail::class);
    }
}
