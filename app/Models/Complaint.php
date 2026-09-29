<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'salon_id',
        'appointment_id',
        'owner_id',
        'admin_id',
        'type',
        'subject',
        'description',
        'image',
        'status',
        'owner_reply',
        'owner_replied_at',
        'client_action',
        'client_actioned_at',
        'escalation_reason',
        'admin_response',
        'admin_actioned_at',
        'admin_question',
        'admin_question_at',
        'owner_deadline_at',
        'owner_statement',
        'owner_statement_at',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'owner_replied_at'    => 'datetime',
        'client_actioned_at'  => 'datetime',
        'admin_actioned_at'   => 'datetime',
        'admin_question_at'   => 'datetime',
        'owner_deadline_at'   => 'datetime',
        'owner_statement_at'  => 'datetime',
        'rejected_at'         => 'datetime',
    ];

    const STATUS_PENDING             = 'pending';
    const STATUS_IN_PROGRESS         = 'in_progress';
    const STATUS_RESOLVED            = 'resolved';
    const STATUS_CLOSED              = 'closed';
    const STATUS_ESCALATED           = 'escalated';
    const STATUS_REJECTED            = 'rejected';
    const STATUS_AWAITING_OWNER      = 'awaiting_owner';
    const STATUS_OWNER_REPLIED_ADMIN = 'owner_replied_admin';

    const TYPE_SERVICE = 'service';
    const TYPE_STAFF   = 'staff';
    const TYPE_PAYMENT = 'payment';
    const TYPE_PRODUCT = 'product';
    const TYPE_OTHER   = 'other';

    const CLIENT_ACTION_ACCEPT   = 'accept';
    const CLIENT_ACTION_ESCALATE = 'escalate';

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function replies()
    {
        return $this->hasMany(ComplaintReply::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeEscalated($query)
    {
        return $query->where('status', self::STATUS_ESCALATED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeBySalon($query, $salonId)
    {
        return $query->where('salon_id', $salonId);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING             => 'badge-warning',
            self::STATUS_IN_PROGRESS         => 'badge-info',
            self::STATUS_RESOLVED            => 'badge-primary',
            self::STATUS_CLOSED              => 'badge-success',
            self::STATUS_ESCALATED           => 'badge-danger',
            self::STATUS_REJECTED            => 'badge-secondary',
            self::STATUS_AWAITING_OWNER      => 'badge-info',
            self::STATUS_OWNER_REPLIED_ADMIN => 'badge-primary',
        ];
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING             => 'Pending',
            self::STATUS_IN_PROGRESS         => 'In Progress',
            self::STATUS_RESOLVED            => 'Resolved',
            self::STATUS_CLOSED              => 'Closed',
            self::STATUS_ESCALATED           => 'Escalated',
            self::STATUS_REJECTED            => 'Rejected',
            self::STATUS_AWAITING_OWNER      => 'Awaiting Owner Response',
            self::STATUS_OWNER_REPLIED_ADMIN => 'Owner Responded to Admin',
        ];
        return $labels[$this->status] ?? 'Pending';
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_SERVICE => 'Service Issue',
            self::TYPE_STAFF   => 'Staff Behavior',
            self::TYPE_PAYMENT => 'Payment Issue',
            self::TYPE_PRODUCT => 'Product Issue',
            self::TYPE_OTHER   => 'Other',
        ];
        return $labels[$this->type] ?? 'Other';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            self::TYPE_SERVICE => 'bi-scissors',
            self::TYPE_STAFF   => 'bi-person-fill',
            self::TYPE_PAYMENT => 'bi-credit-card-fill',
            self::TYPE_PRODUCT => 'bi-box-fill',
            self::TYPE_OTHER   => 'bi-question-circle-fill',
        ];
        return $icons[$this->type] ?? 'bi-question-circle-fill';
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isResolved()
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isEscalated()
    {
        return $this->status === self::STATUS_ESCALATED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isAwaitingOwner()
    {
        return $this->status === self::STATUS_AWAITING_OWNER;
    }

    public function isOwnerRepliedAdmin()
    {
        return $this->status === self::STATUS_OWNER_REPLIED_ADMIN;
    }

    // Admin sirf tab owner se pooch sakta hai jab client ne escalate kiya ho
    public function canAdminAskOwner()
    {
        return $this->status === self::STATUS_ESCALATED;
    }

    // Owner sirf tab reply kar sakta hai jab admin ne sawal poocha ho
    public function canOwnerReplyToAdmin()
    {
        return $this->status === self::STATUS_AWAITING_OWNER;
    }

    // Owner deadline paar kar gaya (admin ko overdue dikhane ke liye)
    public function isOverdue()
    {
        return $this->isAwaitingOwner()
            && $this->owner_deadline_at
            && now()->greaterThan($this->owner_deadline_at);
    }

    public function canClientAccept()
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_REJECTED])
            && is_null($this->client_action);
    }

    public function canClientEscalate()
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_REJECTED])
            && is_null($this->client_action);
    }

    public function isClientActionPending()
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_REJECTED])
            && is_null($this->client_action);
    }
}