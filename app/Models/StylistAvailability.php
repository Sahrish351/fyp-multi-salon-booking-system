<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistAvailability extends Model
{
    protected $fillable = ['stylist_id', 'day_of_week', 'start_time', 'end_time'];
    
    protected $table = 'stylist_availabilities';
    
    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}