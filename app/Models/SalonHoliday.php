<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalonHoliday extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id', 'date', 'reason', 'is_approved'
    ];

    protected $casts = [
        'date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}