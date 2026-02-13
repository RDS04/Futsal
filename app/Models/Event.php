<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
     'judul',
     'tanggal_mulai',
     'tanggal_selesai',
     'status',
     'deskripsi',
     'gambar',
     'region',
     'admin_id',
    ];

    /**
     * Relasi dengan Admin (pembuat event)
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
