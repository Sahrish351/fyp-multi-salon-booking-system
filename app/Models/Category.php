<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'salon_id',      // ✅ Add this
        'name', 
        'slug', 
        'icon', 
        'image', 
        'description', 
        'is_active', 
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ✅ Add this relationship
    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-category.jpg');
    }
}