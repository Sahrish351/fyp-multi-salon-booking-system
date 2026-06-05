<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'salon_id', 'stylist_id', 'slot_date', 'start_time', 'end_time',
        'status', 'locked_by', 'locked_at', 'lock_expires_at',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'locked_at' => 'datetime',
        'lock_expires_at' => 'datetime',
    ];

    public function salon() { return $this->belongsTo(Salon::class); }
    public function stylist() { return $this->belongsTo(Stylist::class); }
    public function lockedByUser() { return $this->belongsTo(User::class, 'locked_by'); }
    public function appointment() { return $this->hasOne(Appointment::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }

    public function isAvailable(): bool { return $this->status === 'available'; }
    public function isLocked(): bool { return $this->status === 'locked'; }
    public function isBooked(): bool { return $this->status === 'booked'; }

    public function isLockExpired(): bool
    {
        return $this->isLocked() && $this->lock_expires_at && now()->isAfter($this->lock_expires_at);
    }
}
