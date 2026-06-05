<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stylist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id', 'name', 'phone', 'email', 'avatar',
        'bio', 'specializations', 'rating', 'is_active',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function salon() { return $this->belongsTo(Salon::class); }
    public function availabilities() { return $this->hasMany(StylistAvailability::class); }
    public function holidays() { return $this->hasMany(StylistHoliday::class); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.jpg');
    }

    public function isAvailableOn(string $day): bool
    {
        return $this->availabilities()
            ->where('day', strtolower($day))
            ->where('is_available', true)
            ->exists();
    }
}
