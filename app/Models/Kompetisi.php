<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompetisi extends Model
{
    protected $table = 'kompetisi';
    public $timestamps = false;

    protected $fillable = [
        'nama_kompetisi',
        'jenis', // ✅ Tambah ini
        'nama_penyelenggara',
        'tingkat_kompetisi',
        'tanggal_kompetisi',
    ];

    protected $casts = [
        'tanggal_kompetisi' => 'date',
    ];

    // ✅ Konstanta Jenis Kompetisi (Kecil semua sesuai request sebelumnya)
    const JENIS_SAINS = 'sains';
    const JENIS_SENI = 'seni';
    const JENIS_OLAHRAGA = 'olahraga';
    const JENIS_TEKNOLOGI = 'teknologi';
    const JENIS_LAINNYA = 'lainnya';

    public static function getJenisOptions()
    {
        return [
            self::JENIS_SAINS,
            self::JENIS_SENI,
            self::JENIS_OLAHRAGA,
            self::JENIS_TEKNOLOGI,
            self::JENIS_LAINNYA,
        ];
    }

    // Tingkat Kompetisi (Sebaiknya juga dibuat kecil semua jika ingin konsisten)
    const TINGKAT_UNIVERSITAS = 'universitas';
    const TINGKAT_NASIONAL = 'nasional';
    const TINGKAT_INTERNASIONAL = 'internasional';
    // ... dst

    // Relasi tetap sama
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_kompetisi')
                    ->using(MahasiswaKompetisi::class);
    }
}