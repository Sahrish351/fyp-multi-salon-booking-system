<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'status',
        'read_at',
        'read_by',
        'reply',
        'replied_at',
        'priority',
        'ip_address',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'status'  => 'read',
            'read_at' => now(),
        ]);
    }
}