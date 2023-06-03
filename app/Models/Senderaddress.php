<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Senderaddress extends Model
{
    use HasFactory;
    const QUADRANT = [
        'SW' => 'South West',
        'SE' => 'South East',
        'NW' => 'North West',
        'NE' => 'North East'
    ];
    protected $guarded = [];

    public function sender(){
        return $this->belongsTo(Sender::class);
    }

    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
}
