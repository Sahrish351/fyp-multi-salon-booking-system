<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'phone', 'email',
        'address', 'city', 'area', 'latitude', 'longitude',
        'logo', 'cover_image', 'status', 'rejection_reason', 'cnic',
        'is_featured', 'rating', 'total_reviews', 'open_time', 'close_time', 'working_days',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
        'working_days' => 'array',
    ];

    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function documents() { return $this->hasMany(SalonDocument::class); }
    public function paymentDetails() { return $this->hasMany(SalonPaymentDetail::class); }
    public function services() { return $this->hasMany(Service::class); }
    public function stylists() { return $this->hasMany(Stylist::class); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function gallery() { return $this->hasMany(Gallery::class); }
    public function favorites() { return $this->hasMany(Favorite::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }
    public function complaints() { return $this->hasMany(Complaint::class); }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isSuspended(): bool { return $this->status === 'suspended'; }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-salon.jpg');
    }
}
