<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lapangan extends Model
{
    protected $table = "lapangans";
    protected $fillable = [
        'namaLapangan',
        'jenisLapangan',
        'harga',
        'status',
        'deskripsi',
        'gambar',
        'region',
        'admin_id',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /**
     * Relasi dengan Admin (pemilik lapangan)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Scope untuk filter berdasarkan region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope untuk filter berdasarkan admin yang login
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }
}
