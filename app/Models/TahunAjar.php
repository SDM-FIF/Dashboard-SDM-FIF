<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjar extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajar';

    protected $fillable = [
        'tahun',
        'semester',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    protected $appends = [
        'label',
        'nama_semester'
    ];

    /**
     * Relasi ke Jadwal Pengujian
     */
    public function jadwalPengujian()
    {
        return $this->hasMany(JadwalPengujian::class);
    }

    /**
     * Accessor untuk label tahun ajar
     * Contoh: "2024/2025 Ganjil" atau "2024/2025 Genap"
     */
    public function getLabelAttribute()
    {
        $semesterText = $this->semester == '1' ? 'Ganjil' : 'Genap';
        $tahunAkhir = $this->tahun + 1;
        
        return "{$this->tahun}/{$tahunAkhir} {$semesterText}";
    }

    /**
     * Accessor untuk nama semester
     */
    public function getNamaSemesterAttribute()
    {
        return $this->semester == '1' ? 'Ganjil' : 'Genap';
    }

    /**
     * Scope untuk filter semester ganjil
     */
    public function scopeGanjil($query)
    {
        return $query->where('semester', '1');
    }

    /**
     * Scope untuk filter semester genap
     */
    public function scopeGenap($query)
    {
        return $query->where('semester', '2');
    }

    /**
     * Scope untuk tahun tertentu
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}