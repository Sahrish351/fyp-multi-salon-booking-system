<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id',
        'salon_id',
        'stylist_id',
        'appointment_id',
        'rating',
        'comment',
        'is_approved',
        'is_flagged',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_flagged' => 'boolean',
    ];

    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
    public function stylist() { return $this->belongsTo(Stylist::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function reply() { return $this->hasOne(ReviewReply::class); }

    
    public function getReviewerNameAttribute(): string
    {
        $person = $this->client ?? $this->user;

        return $person->name ?? 'Client';
    }
}