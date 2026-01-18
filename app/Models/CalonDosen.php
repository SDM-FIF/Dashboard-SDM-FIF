<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonDosen extends Model
{
    use HasFactory;

    protected $table = 'calon_dosen';

    protected $fillable = [
        'prodi_id',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_telepon',
        'alamat',
        'prodi_pendidikan_s1',
        'nama_kampus_pendidikan_s1',
        'ipk_s1',
        'prodi_pendidikan_s2',
        'nama_kampus_pendidikan_s2',
        'ipk_s2',
        'prodi_pendidikan_s3',
        'nama_kampus_pendidikan_s3',
        'ipk_s3',
        'jabatan_fungsional_akademik',
        'prodi_tujuan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk_s1' => 'decimal:2',
        'ipk_s2' => 'decimal:2',
        'ipk_s3' => 'decimal:2',
    ];

    /**
     * Relasi ke Prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    /**
     * Relasi ke Jadwal Pengujian
     */
    public function jadwalPengujian()
    {
        return $this->hasMany(JadwalPengujian::class);
    }

    /**
     * Accessor untuk nama lengkap dengan gelar
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama;
    }

    /**
     * Accessor untuk pendidikan terakhir
     */
    public function getPendidikanTerakhirAttribute()
    {
        if ($this->ipk_s3) return 'S3';
        if ($this->ipk_s2) return 'S2';
        if ($this->ipk_s1) return 'S1';
        return '-';
    }
}