<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasRoles, Notifiable;  

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
        'avatar', 'city', 'area', 'is_active', 'is_verified',
        'google_id', 'auth_provider', 'theme',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'password' => 'hashed',
    ];

    // Accessor for dynamic Avatar URL
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF6B9D&color=fff&size=100';
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isClient(): bool { return $this->role === 'client'; }

    public function salons() { return $this->hasMany(Salon::class, 'owner_id'); }
    public function appointments() { return $this->hasMany(Appointment::class, 'client_id'); }
    public function payments() { return $this->hasMany(Payment::class, 'client_id'); }
    public function reviews() { return $this->hasMany(Review::class, 'client_id'); }
    public function favorites() { return $this->hasMany(Favorite::class, 'client_id'); }
    public function waitlists() { return $this->hasMany(Waitlist::class, 'client_id'); }
    public function complaints() { return $this->hasMany(Complaint::class, 'client_id'); }
    public function auditLogs() { return $this->hasMany(AuditLog::class); }

    public function favoriteSalons()
    {
        return $this->belongsToMany(Salon::class, 'favorites', 'client_id', 'salon_id');
    }
}