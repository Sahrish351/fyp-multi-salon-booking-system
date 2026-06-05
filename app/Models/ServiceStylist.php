<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStylist extends Model
{
    protected $table = 'service_stylist';

    protected $fillable = ['service_id', 'stylist_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}