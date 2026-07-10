<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'generated_by', 'salon_id', 'type', 'format',
        'file_path', 'from_date', 'to_date', 'status',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function getFileUrlAttribute(): string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : '';
    }

    public function getFileSizeAttribute(): string
    {
        if ($this->file_path && \Storage::disk('public')->exists($this->file_path)) {
            $size = \Storage::disk('public')->size($this->file_path);
            return round($size / 1024 / 1024, 1) . ' MB';
        }
        return 'N/A';
    }
}