<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id', 'name', 'description', 'price', 'duration',
        'image', 'is_active', 'services_list'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'services_list' => 'array',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}