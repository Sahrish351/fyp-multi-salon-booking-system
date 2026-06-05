<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id', 'client_id', 'salon_id', 'transaction_ref',
        'amount', 'method', 'sender_number', 'screenshot',
        'status', 'rejection_reason', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function getScreenshotUrlAttribute(): string
    {
        return $this->screenshot ? asset('storage/' . $this->screenshot) : '';
    }
}
