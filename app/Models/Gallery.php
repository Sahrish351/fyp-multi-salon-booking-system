<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['salon_id', 'category_id', 'image_path', 'caption', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function salon() { return $this->belongsTo(Salon::class); }
    public function category() { return $this->belongsTo(Category::class); }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
