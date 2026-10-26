<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanPengembanganTpa extends Model
{
    protected $table = 'kegiatan_pengembangan_tpa';

    protected $fillable = [
        'kegiatan',
        'tahun',
        'jumlah',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah' => 'integer',
    ];
}
