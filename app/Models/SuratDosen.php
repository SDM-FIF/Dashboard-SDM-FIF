<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratDosen extends Model
{
    use HasFactory;

    protected $table = 'surat_dosen';

    protected $fillable = [
        'dosen_id',
        'jenis_surat',
        'nomor_surat',
        'judul_surat',
        'tanggal_surat',
        'berlaku_mulai',
        'berlaku_selesai',
        'kategori',
        'file_surat',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'berlaku_mulai' => 'date',
        'berlaku_selesai' => 'date',
    ];

    /**
     * Relationship to Primary Dosen (backward compatibility)
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Relationship to Multiple Dosen Recipients
     */
    public function dosenList()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_surat', 'surat_dosen_id', 'dosen_id');
    }
}
