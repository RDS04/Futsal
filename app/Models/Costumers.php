<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Costumers extends Authenticatable
{
    use Notifiable;

    protected $table = 'costumers';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'password',
        'phone',
        'address',
        'role',
        'payment_status',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Encrypt password saat disimpan
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt password saat diakses
     */
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Relasi dengan Boking - satu customer bisa punya banyak booking
     */
    public function bokings()
    {
        return $this->hasMany(Boking::class, 'customer_id', 'id');
    }
}
