<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonDosen extends Model
{
    use HasFactory;

    protected $table = 'calon_dosen';

    protected $fillable = [
        'no_registrasi',
        'prodi_id',
        'tahun_ajar_id',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_telepon',
        'alamat',
        'jabatan_fungsional_akademik',
        'bidang_keahlian',
        'status_penerimaan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Constants untuk status penerimaan
    const STATUS_SELEKSI = 'Seleksi';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_SELEKSI,
            self::STATUS_DITERIMA,
            self::STATUS_DITOLAK,
        ];
    }

    /**
     * Relasi ke Tahun Ajar
     */
    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
    }

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
        // Ambil riwayat pendidikan terakhir berdasarkan jenjang tertinggi
        $riwayat = $this->riwayatPendidikan()->orderByRaw("FIELD(jenjang, 's3', 's2', 's1')")->first();
        
        if ($riwayat) {
            return strtoupper($riwayat->jenjang);
        }
        
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