<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengujian extends Model
{
    protected $table = 'jadwal_pengujian';
    
    public $timestamps = false;

    protected $fillable = [
        'dosen_penguji_id',
        'rekrutasi_dosen_id',
        'jadwal_ujian',
    ];

    protected $casts = [
        'jadwal_ujian' => 'date',
    ];

    // Relasi
    public function dosenPenguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    public function rekrutasiDosen()
    {
        return $this->belongsTo(RekrutasiDosen::class);
    }

    public function hasilPengujian()
    {
        return $this->hasOne(HasilPengujian::class);
    }
}
