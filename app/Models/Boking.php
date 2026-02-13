<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Boking extends Model
{
    protected $table = 'bokings';

    protected $fillable = [
        'nama',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'lapangan',
        'catatan',
        'region',
        'customer_id',
        'lapangan_id',
        'total_harga',
        'harga_per_jam',
        'durasi',
        'order_id',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Relasi dengan Customer yang melakukan booking
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Costumers::class, 'customer_id');
    }

    /**
     * Relasi dengan Lapangan yang di-booking
     */
    public function lapanganData(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    /**
     * Relasi dengan Payment untuk tracking pembayaran
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'booking_id', 'id');
    }

    /**
     * Scope untuk filter berdasarkan region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope untuk filter berdasarkan customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
