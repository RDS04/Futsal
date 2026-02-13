<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'password',
        'region',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi Admin dengan Lapangan
     */
    public function lapangans(): HasMany
    {
        return $this->hasMany(Lapangan::class);
    }

    /**
     * Relasi Admin dengan Event
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Relasi Admin dengan Slider
     */
    public function sliders(): HasMany
    {
        return $this->hasMany(Slider::class);
    }

    /**
     * Get nama region dengan format yang lebih bagus
     */
    public function getRegionLabelAttribute()
    {
        $labels = [
            'bypass' => 'Bypass',
            'batusangkar' => 'BatuSangkar',
            'ulakkarang' => 'Ulak Karang',
        ];
        return $labels[$this->region] ?? $this->region;
    }

    /**
     * Get label role (master atau regional)
     */
    public function getRoleLabelAttribute()
    {
        $labels = [
            'master' => 'Master Admin (CEO)',
            'regional' => 'Regional Admin',
        ];
        return $labels[$this->role] ?? $this->role;
    }

    /**
     * Helper: Cek apakah admin adalah master
     */
    public function isMaster(): bool
    {
        return $this->role === 'master';
    }

    /**
     * Helper: Cek apakah admin adalah regional
     */
    public function isRegional(): bool
    {
        return $this->role === 'regional';
    }

    /**
     * Helper: Cek apakah admin bisa akses region tertentu
     */
    public function canAccessRegion(string $region): bool
    {
        // Master bisa akses semua region
        if ($this->isMaster()) {
            return true;
        }
        // Regional hanya bisa akses region mereka
        return $this->region === $region;
    }
}
