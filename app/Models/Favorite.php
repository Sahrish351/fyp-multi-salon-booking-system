<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['client_id', 'salon_id'];

    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function salon() { return $this->belongsTo(Salon::class); }
}
