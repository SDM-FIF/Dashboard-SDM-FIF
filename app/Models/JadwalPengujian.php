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
        'dosen_penguji_id',
        'rekrutasi_dosen_id',
        'jadwal_ujian',
        'gedung',      // ✅ Tambahan baru
        'ruangan',     // ✅ Tambahan baru
        'waktu',       // ✅ Tambahan baru
        'status_dosen',
        'jenis_kelamin',
    ];

    protected $casts = [
        'jadwal_ujian' => 'date',
        'waktu' => 'datetime:H:i', // ✅ Cast waktu ke format jam:menit
    ];

    // Konstanta untuk status dosen
    const STATUS_SELEKSI = 'Seleksi';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';

     // Konstanta untuk jenis kelamin
    const JENIS_KELAMIN_LAKI = 'Laki-laki';
    const JENIS_KELAMIN_PEREMPUAN = 'Perempuan';

    public static function getStatusDosenOptions()
    {
        return [
            self::STATUS_SELEKSI,
            self::STATUS_DITERIMA,
            self::STATUS_DITOLAK,
        ];
    }

    public static function getJenisKelaminOptions()
    {
        return [
            self::JENIS_KELAMIN_LAKI,
            self::JENIS_KELAMIN_PEREMPUAN,
        ];
    }

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
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    public function rekrutasiDosen()
    {
        return $this->belongsTo(RekrutasiDosen::class);
    }

    public function hasilPengujian()
    {
        return $this->hasOne(HasilPengujian::class);
    }
}