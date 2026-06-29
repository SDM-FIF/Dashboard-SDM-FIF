<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengujian extends Model
{
    protected $table = 'jadwal_pengujian';
    
    public $timestamps = false;

    protected $fillable = [
        'tahun_ajar_id',
        'calon_dosen_id',
        'jadwal_ujian',
        'metode_pelaksanaan',
        'gedung',
        'ruangan',
        'waktu',
    ];

    protected $casts = [
        'jadwal_ujian' => 'date',
        'waktu' => 'datetime:H:i',
    ];

    // Relasi
    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class);
    }

    public function calonDosen()
    {
        return $this->belongsTo(CalonDosen::class);
    }

    public function dosenPenguji()
    {
        return $this->belongsToMany(Dosen::class, 'jadwal_pengujian_dosen')
                    ->withPivot('urutan')
                    ->withTimestamps()
                    ->orderBy('urutan');
    }

    public function penilaianDetails()
    {
        return $this->hasMany(PenilaianDetail::class, 'jadwal_pengujian_id');
    }

    public function hasilPengujian()
    {
        return $this->hasOne(HasilPengujian::class);
    }
}