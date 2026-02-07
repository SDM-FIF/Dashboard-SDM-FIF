<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'status_pegawai',
        'pendidikan_terakhir',
        'sertifikasi_dosen',      // ✅ Tambahan baru
        'tanggal_serdos',         // ✅ Tambahan baru
        'foto_profil',           // ✅ Tambahan baru
        'status_dosen',           // ✅ Tambahan baru
    ];

    protected $casts = [
        'sertifikasi_dosen' => 'boolean',
        'tanggal_serdos' => 'date',
    ];

    // Konstanta untuk Status Dosen
    const STATUS_AKTIF = 'Aktif';
    const STATUS_TUGAS_BELAJAR = 'Tugas Belajar';
    const STATUS_IZIN_BELAJAR = 'Izin Belajar';
    const STATUS_CLTY = 'CLTY';

    public static function getStatusDosenOptions()
    {
        return [
            self::STATUS_AKTIF,
            self::STATUS_TUGAS_BELAJAR,
            self::STATUS_IZIN_BELAJAR,
            self::STATUS_CLTY,
        ];
    }

    // Accessor untuk mendapatkan URL foto profile
    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }
        
        // Default foto jika tidak ada
        return asset('images/default-avatar.png');
    }

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

    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikanDosen::class);
    }
}