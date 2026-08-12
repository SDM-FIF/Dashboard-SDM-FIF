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
     * Relationship to Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
