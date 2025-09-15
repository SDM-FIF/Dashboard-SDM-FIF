<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokKeahlian extends Model
{
    protected $table = 'kelompok_keahlian';
    
    public $timestamps = false;

    protected $fillable = [
        'nama_kelompok_keahlian',
    ];

    // Relasi
    public function dosen()
    {
        return $this->hasMany(Dosen::class);
    }
}
