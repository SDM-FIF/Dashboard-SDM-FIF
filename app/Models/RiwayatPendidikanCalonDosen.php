<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RiwayatPendidikanCalonDosen extends Model
{
    protected $table = 'riwayat_pendidikan_calon_dosen';

    protected $fillable = [
        'calon_dosen_id',
        'jenjang',
        'nama_universitas',
        'prodi_pendidikan',
        'tanggal_lulus',
        'ijazah',
        'transkrip_nilai',
    ];

    protected $casts = [
        'tanggal_lulus' => 'date',
    ];

    // Konstanta untuk Jenjang Pendidikan
    const JENJANG_S1 = 'S1';
    const JENJANG_S2 = 'S2';
    const JENJANG_S3 = 'S3';

    public static function getJenjangOptions()
    {
        return [
            self::JENJANG_S1,
            self::JENJANG_S2,
            self::JENJANG_S3,
        ];
    }

    // Accessor untuk mendapatkan URL file ijazah
    public function getIjazahUrlAttribute()
    {
        if ($this->ijazah) {
            return Storage::url($this->ijazah);
        }
        return null;
    }

    // Accessor untuk mendapatkan URL file transkrip
    public function getTranskripNilaiUrlAttribute()
    {
        if ($this->transkrip_nilai) {
            return Storage::url($this->transkrip_nilai);
        }
        return null;
    }

    // Helper method untuk cek ekstensi file
    public function getIjazahFileTypeAttribute()
    {
        if (!$this->ijazah) return null;
        
        $extension = pathinfo($this->ijazah, PATHINFO_EXTENSION);
        return strtolower($extension);
    }

    public function getTranskripFileTypeAttribute()
    {
        if (!$this->transkrip_nilai) return null;
        
        $extension = pathinfo($this->transkrip_nilai, PATHINFO_EXTENSION);
        return strtolower($extension);
    }

    // Relasi ke CalonDosen
    public function calonDosen()
    {
        return $this->belongsTo(CalonDosen::class);
    }
}
