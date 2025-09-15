<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompetisi extends Model
{
    protected $table = 'kompetisi';
    
    public $timestamps = false;

    protected $fillable = [
        'nama_kompetisi',
        'nama_penyelenggara',
        'tingkat_kompetisi',
        'tanggal_kompetisi',
    ];

    protected $casts = [
        'tanggal_kompetisi' => 'date',
    ];

    // Enum untuk tingkat kompetisi
    const TINGKAT_UNIVERSITAS = 'Universitas';
    const TINGKAT_KABUPATEN_KOTA = 'Kabupaten/Kota';
    const TINGKAT_PROVINSI = 'Provinsi';
    const TINGKAT_NASIONAL = 'Nasional';
    const TINGKAT_INTERNASIONAL = 'Internasional';

    public static function getTingkatKompetisiOptions()
    {
        return [
            self::TINGKAT_UNIVERSITAS,
            self::TINGKAT_KABUPATEN_KOTA,
            self::TINGKAT_PROVINSI,
            self::TINGKAT_NASIONAL,
            self::TINGKAT_INTERNASIONAL,
        ];
    }

    // Relasi
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_kompetisi')
                    ->using(MahasiswaKompetisi::class);
    }

    public function mahasiswaKompetisi()
    {
        return $this->hasMany(MahasiswaKompetisi::class);
    }
}
