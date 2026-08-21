<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenagaPendukungAkademik extends Model
{
    protected $table = 'tenaga_pendukung_akademik';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'jabatan',
        'status_pegawai',
        'lokasi_kerja',
        'pendidikan_terakhir',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Surat Tugas & SK Dosen received by this TPA
     */
    public function suratDosen()
    {
        return $this->belongsToMany(SuratDosen::class, 'tpa_surat', 'tpa_id', 'surat_dosen_id')
                    ->withPivot('jabatan')
                    ->withTimestamps();
    }
}
