<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonDocument extends Model
{
    protected $fillable = ['salon_id', 'type', 'file_path', 'original_name'];

    public function salon() { return $this->belongsTo(Salon::class); }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
