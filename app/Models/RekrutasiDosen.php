<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekrutasiDosen extends Model
{
    protected $table = 'rekrutasi_dosen';

    protected $fillable = [
        'no_registrasi',
        'nama_calon',
        'prodi_id',
        'tahun_ajar',
        'tanggal_pengujian',
        'jadwal',
        'status',
    ];

    protected $casts = [
        'tanggal_pengujian' => 'date',
    ];

    // Enum untuk status
    const STATUS_DIAJUKAN = 'Diajukan';
    const STATUS_DIPROSES = 'Diproses';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_DIAJUKAN,
            self::STATUS_DIPROSES,
            self::STATUS_DITERIMA,
            self::STATUS_DITOLAK,
        ];
    }

    // Relasi dengan Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    // Relasi dengan Jadwal Pengujian
    public function jadwalPengujian()
    {
        return $this->hasMany(JadwalPengujian::class, 'rekrutasi_dosen_id');
    }

    // Relasi dengan Hasil Pengujian (through Jadwal Pengujian)
    public function hasilPengujian()
    {
        return $this->hasManyThrough(
            HasilPengujian::class,
            JadwalPengujian::class,
            'rekrutasi_dosen_id',
            'jadwal_pengujian_id',
            'id',
            'id'
        );
    }

    // Helper method untuk generate no registrasi otomatis
    public static function generateNoRegistrasi()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastRecord = self::whereYear('created_at', $year)
                         ->whereMonth('created_at', $month)
                         ->orderBy('id', 'desc')
                         ->first();
        
        $sequence = $lastRecord ? intval(substr($lastRecord->no_registrasi, -4)) + 1 : 1;
        
        return sprintf('REK-%s%s-%04d', $year, $month, $sequence);
    }
}