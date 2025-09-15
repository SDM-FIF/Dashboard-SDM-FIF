<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekrutasiDosen extends Model
{
    protected $table = 'rekrutasi_dosen';
    
    public $timestamps = false;

    protected $fillable = [
        'nama_calon',
        'tanggal_pengujian',
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

    // Relasi
    public function jadwalPengujian()
    {
        return $this->hasMany(JadwalPengujian::class);
    }
}
