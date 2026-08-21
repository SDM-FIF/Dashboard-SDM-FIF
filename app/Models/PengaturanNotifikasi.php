<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanNotifikasi extends Model
{
    protected $table = 'pengaturan_notifikasi';
    protected $fillable = ['fitur', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Check if a feature's notification is enabled.
     */
    public static function isEnabled(string $fitur): bool
    {
        $setting = self::where('fitur', $fitur)->first();
        return $setting ? (bool) $setting->is_enabled : true;
    }
}
