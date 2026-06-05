<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonPaymentDetail extends Model
{
    protected $fillable = ['salon_id', 'method', 'account_number', 'account_name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function salon() { return $this->belongsTo(Salon::class); }
}
