<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    
    public $timestamps = false;

    protected $fillable = [
        'nama_fakultas',
    ];

    // Relasi
    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
