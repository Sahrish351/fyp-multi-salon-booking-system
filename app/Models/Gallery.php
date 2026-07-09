<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Gallery extends Model
{
    

    protected $table = 'galleries';

    protected $fillable = [
        'salon_id',
        'category_id',
        'image_path',
        'caption',
        'sort_order',
        'is_active',
        'views',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}