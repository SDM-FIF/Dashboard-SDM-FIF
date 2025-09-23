<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'prodi_id',
        'kelompok_keahlian_id',
        'front_title',
        'nama_lengkap',
        'back_title',
        'jabatan',
        'nip',
        'kode_dosen',
        'lokasi_kerja',
        'status_pegawai', // Kolom baru
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kelompokKeahlian()
    {
        return $this->belongsTo(KelompokKeahlian::class);
    }

    public function jadwalPengujianSebagaiPenguji()
    {
        return $this->hasMany(JadwalPengujian::class, 'dosen_penguji_id');
    }
}
