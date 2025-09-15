<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MahasiswaKompetisi extends Pivot
{
    protected $table = 'mahasiswa_kompetisi';
    
    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'kompetisi_id',
    ];

    // Relasi
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kompetisi()
    {
        return $this->belongsTo(Kompetisi::class);
    }
}
