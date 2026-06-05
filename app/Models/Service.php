<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id', 'category_id', 'name', 'description',
        'price', 'duration', 'image', 'is_active', 'is_package',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_package' => 'boolean',
    ];

    public function salon() { return $this->belongsTo(Salon::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }

    public function getDurationTextAttribute(): string
    {
        $hours = intdiv($this->duration, 60);
        $minutes = $this->duration % 60;
        if ($hours > 0 && $minutes > 0) return "{$hours}h {$minutes}min";
        if ($hours > 0) return "{$hours}h";
        return "{$minutes}min";
    }
}
