<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // SoftDeletes commented out to fix the "deleted_at column not found" error

class Category extends Model
{
    // use SoftDeletes; // SoftDeletes trait commented out to prevent the query from adding "where deleted_at is null"

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salon_id',      // Foreign key for the salon
        'name',          // Category name
        'slug',          // URL friendly slug
        'icon',          // Icon class or path
        'image',         // Category image path
        'description',   // Category description
        'is_active',     // Status flag (1 = active, 0 = inactive)
        'sort_order'     // Display order
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean', // Cast is_active to boolean
    ];

    /**
     * Get the salon that owns this category.
     */
    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Get the services associated with this category.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the galleries associated with this category.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the full image URL attribute.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-category.jpg');
    }
}