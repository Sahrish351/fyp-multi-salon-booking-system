<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonWorkingHour extends Model
{
    protected $fillable = ['salon_id', 'day', 'open_time', 'close_time', 'is_closed'];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}