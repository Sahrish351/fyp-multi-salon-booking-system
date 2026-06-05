<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id', 'salon_id', 'appointment_id',
        'type', 'subject', 'description', 'status', 'priority',
    ];

    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function replies() { return $this->hasMany(ComplaintReply::class); }
}
