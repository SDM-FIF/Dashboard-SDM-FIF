<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    public $timestamps = false;

    protected $fillable = [
        'nama_fakultas',
        'dekan_id',
        'wadek1_id',
        'wadek2_id',
    ];

    // Relasi ke Dosen sebagai Dekan
    public function dekan()
    {
        return $this->belongsTo(Dosen::class, 'dekan_id');
    }

    // Relasi ke Dosen sebagai Wadek 1
    public function wadek1()
    {
        return $this->belongsTo(Dosen::class, 'wadek1_id');
    }

    // Relasi ke Dosen sebagai Wadek 2
    public function wadek2()
    {
        return $this->belongsTo(Dosen::class, 'wadek2_id');
    }

    // ... relasi prodi dan users tetap ada
}