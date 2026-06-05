<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $fillable = [
        'client_id', 'salon_id', 'stylist_id', 'service_id', 'time_slot_id',
        'preferred_date', 'position', 'status', 'notified_at', 'responded_at', 'expires_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
    public function stylist() { return $this->belongsTo(Stylist::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function timeSlot() { return $this->belongsTo(TimeSlot::class); }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }
}
