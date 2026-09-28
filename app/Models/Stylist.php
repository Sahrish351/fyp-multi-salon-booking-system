<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stylist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id', 'name', 'role', 'phone', 'email', 'avatar',
        'bio', 'specializations', 'experience_years', 'rating',
        'status', 'is_active',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Stylist delete hone par uske khali time slots bhi delete karo.
     */
    protected static function booted()
    {
        static::deleting(function (Stylist $stylist) {
            if ($stylist->isForceDeleting()) {
                return;
            }

            // Sirf wo slots delete karo jinke saath koi appointment nahi hai
            $stylist->timeSlots()
                ->whereDoesntHave('appointment', function ($q) {
                    $q->withTrashed();
                })
                ->delete();
        });
    }

    public function salon() { return $this->belongsTo(Salon::class); }
    public function availabilities() { return $this->hasMany(StylistAvailability::class); }
    public function holidays() { return $this->hasMany(StylistHoliday::class); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }

    // Is stylist ki services (service_stylist table se)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_stylist')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'stylist_id');
    }

    // Purane code/views jo $stylist->photo use karte hain, wo avatar se chalenge
    public function getPhotoAttribute()
    {
        return $this->avatar;
    }

    public function setPhotoAttribute($value)
    {
        $this->attributes['avatar'] = $value;
    }

    // Purane code jo $stylist->specialization use karta hai
    public function getSpecializationAttribute()
    {
        return $this->specializations;
    }

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