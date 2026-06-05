<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = ['phone', 'email', 'otp', 'type', 'is_used', 'expires_at'];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isValid(string $otp): bool
    {
        return !$this->is_used && !$this->isExpired() && $this->otp === $otp;
    }
}
