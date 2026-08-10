<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    // Tabel menggunakan nama singular berdasarkan migrasi
    protected $table = 'user';

    protected $fillable = [
        'fakultas_id',
        'prodi_id',
        'role_id',
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

    /**
     * Get the active role name from the session or default to the first assigned role.
     */
    public function getActiveRoleAttribute()
    {
        if (app()->runningInConsole()) {
            return $this->roles->first()->name ?? null;
        }

        if (session() && session()->has('active_role')) {
            $activeRole = session()->get('active_role');
            // Ensure the user actually has this role assigned in the database
            if ($this->roles->contains('name', $activeRole)) {
                return $activeRole;
            }
        }
        
        $firstRole = $this->roles->first();
        if ($firstRole) {
            if (session()) {
                session()->put('active_role', $firstRole->name);
            }
            return $firstRole->name;
        }
        
        return null;
    }

    /**
     * Override Spatie HasRoles' hasRole method to only match the active role in session.
     */
    public function hasRole($roles, ?string $guard = null): bool
    {
        $activeRole = $this->active_role;
        if (!$activeRole) {
            return false;
        }

        if (is_string($roles)) {
            return $activeRole === $roles;
        }

        if (is_array($roles)) {
            return in_array($activeRole, $roles);
        }

        if ($roles instanceof \Illuminate\Support\Collection) {
            return $roles->contains('name', $activeRole);
        }

        if ($roles instanceof \Spatie\Permission\Models\Role) {
            return $activeRole === $roles->name;
        }

        return false;
    }

    // Relasi
    public function fakultas() 
    { 
        return $this->belongsTo(Fakultas::class); 
    }
    
    public function prodi() 
    { 
        return $this->belongsTo(Prodi::class); 
    }
    
    public function role() 
    { 
        return $this->belongsTo(Role::class); 
    }
    
    public function dosen() 
    { 
        return $this->hasOne(Dosen::class); 
    }
    
    public function tenagaPendukung() 
    { 
        return $this->hasOne(TenagaPendukungAkademik::class); 
    }
}
