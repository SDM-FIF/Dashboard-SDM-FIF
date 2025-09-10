<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    // kalau tabelmu bernama 'users' biarkan default; kalau 'user' (singular) set ini:
    // protected $table = 'user';

    protected $fillable = [
        'fakultas_id',
        'prodi_id',
        'nama_lengkap',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // guard (opsional, default 'web')
    protected $guard_name = 'web';

    public function fakultas() { return $this->belongsTo(Fakultas::class); }
    public function prodi() { return $this->belongsTo(Prodi::class); }
    public function dosen() { return $this->hasOne(Dosen::class); }
    public function tenagaPendukung() { return $this->hasOne(TenagaPendukungAkademik::class); }
}
