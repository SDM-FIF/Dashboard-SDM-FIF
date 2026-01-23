<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonDosen extends Model
{
    use HasFactory;

    protected $table = 'calon_dosen';

    protected $fillable = [
        'no_registrasi',              // ✅ Tambahan baru
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
        'bidang_keahlian',            // ✅ Tambahan baru
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
     * Relasi ke Riwayat Pendidikan
     */
    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikanCalonDosen::class);
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

    /**
     * Generate nomor registrasi otomatis dengan format: CAL-YYYYMMDD-XXXX
     */
    public static function generateNoRegistrasi(): string
    {
        $date = now()->format('Ymd');
        $prefix = "CAL-{$date}-";
        
        // Cari nomor registrasi terakhir hari ini
        $lastRegistration = self::where('no_registrasi', 'like', "{$prefix}%")
            ->orderBy('no_registrasi', 'desc')
            ->first();
        
        if ($lastRegistration) {
            // Ambil 4 digit terakhir dan tambahkan 1
            $lastNumber = intval(substr($lastRegistration->no_registrasi, -4));
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada, mulai dari 1
            $newNumber = 1;
        }
        
        // Format menjadi 4 digit dengan leading zero
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto-generate no_registrasi saat create
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->no_registrasi)) {
                $model->no_registrasi = self::generateNoRegistrasi();
            }
        });
    }
}