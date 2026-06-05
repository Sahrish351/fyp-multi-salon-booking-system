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

    public function generatedBy() { return $this->belongsTo(User::class, 'generated_by'); }
    public function salon() { return $this->belongsTo(Salon::class); }

    public function getFileUrlAttribute(): string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : '';
    }
}
