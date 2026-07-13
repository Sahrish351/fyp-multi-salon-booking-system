<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintReply extends Model
{
    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
        'sender_type',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderLabelAttribute()
    {
        return match($this->sender_type) {
            'client' => 'Client',
            'owner' => 'Salon Owner',
            'admin' => 'Admin',
            default => $this->sender_type,
        };
    }

    public function getSenderIconAttribute()
    {
        return match($this->sender_type) {
            'client' => 'bi-person-fill',
            'owner' => 'bi-shop',
            'admin' => 'bi-shield-fill',
            default => 'bi-person-fill',
        };
    }
}