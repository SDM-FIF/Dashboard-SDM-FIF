<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    protected $table = 'penilaian_detail';

    protected $fillable = [
        'komponen_penilaian_id',
        'hasil_pengujian_id',
        'skor',
        'catatan',
    ];

    // Relasi
    public function komponenPenilaian()
    {
        return $this->belongsTo(KomponenPenilaian::class);
    }

    public function hasilPengujian()
    {
        return $this->belongsTo(HasilPengujian::class);
    }
}
