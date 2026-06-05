<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_ref', 'client_id', 'salon_id', 'stylist_id', 'service_id',
        'time_slot_id', 'appointment_date', 'start_time', 'end_time',
        'total_amount', 'advance_amount', 'status', 'notes',
        'cancellation_reason', 'cancelled_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'total_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
    public function stylist() { return $this->belongsTo(Stylist::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function timeSlot() { return $this->belongsTo(TimeSlot::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function review() { return $this->hasOne(Review::class); }
    public function complaint() { return $this->hasOne(Complaint::class); }

    public function isPending(): bool { return $this->status === 'pending_payment'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }

    public static function generateRef(): string
    {
        return 'GLM-' . strtoupper(uniqid());
    }
}
