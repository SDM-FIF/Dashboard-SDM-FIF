<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    
    public $timestamps = false;

    protected $fillable = [
        'prodi_id',
        'nama_lengkap',
        'nim',
        'status',
    ];

    // Relasi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kompetisi()
    {
        return $this->belongsToMany(Kompetisi::class, 'mahasiswa_kompetisi')
                    ->using(MahasiswaKompetisi::class);
    }

    public function mahasiswaKompetisi()
    {
        return $this->hasMany(MahasiswaKompetisi::class);
    }
}
