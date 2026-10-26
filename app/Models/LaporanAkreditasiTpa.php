<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAkreditasiTpa extends Model
{
    protected $table = 'laporan_akreditasi_tpa';

    protected $fillable = [
        'key',
        'value',
    ];
}
