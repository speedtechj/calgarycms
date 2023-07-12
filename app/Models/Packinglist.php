<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packinglist extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'packlistdoc' => 'array',
        'waverdoc' => 'array',
    ];
    public function packlistitem(){
        return $this->belongsTo(Packlistitem::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }
    public function sender(){
        return $this->belongsTo(Sender::class);
    }
}
