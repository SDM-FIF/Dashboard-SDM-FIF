<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPeriodeCrud extends Model
{
    protected $table = 'pengaturan_periode_crud';

    protected $fillable = [
        'fitur',
        'mode',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
