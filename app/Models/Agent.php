<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'filedoc' => 'array',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }
}
