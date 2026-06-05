<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistHoliday extends Model
{
    protected $fillable = ['stylist_id', 'holiday_date', 'reason'];
    
    protected $table = 'stylist_holidays';
    
    protected $casts = [
        'holiday_date' => 'date',
    ];
    
    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}