<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSlot extends Model
{
    protected $fillable = [
        'salon_id',
        'stylist_id',
        'slot_date',
        'start_time',
        'end_time',
        'status',
        'locked_by',
        'locked_at',
        'lock_expires_at',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'locked_at' => 'datetime',
        'lock_expires_at' => 'datetime',
    ];

    // ✅ YEH METHOD ADD KARO
    public function isAvailable()
    {
        // If status is 'booked', not available
        if ($this->status === 'booked') {
            return false;
        }
        
        // If status is 'locked', check if lock expired
        if ($this->status === 'locked') {
            // If lock expired, it's available again
            if ($this->lock_expires_at && now()->greaterThan($this->lock_expires_at)) {
                return true;
            }
            return false;
        }
        
        // 'available' or 'blocked' status check
        return $this->status === 'available';
    }
    
    // Relationship with salon
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
    
    // Relationship with stylist
    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }
}