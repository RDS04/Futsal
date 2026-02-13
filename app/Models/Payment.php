<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'payment_status',
        'amount',
        'payment_method',
        'payment_reference',
        'midtrans_response',
        'signature_key',
        'payment_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'payment_at' => 'datetime',
    ];

    /**
     * Relasi dengan Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Boking::class, 'booking_id', 'id');
    }
}

