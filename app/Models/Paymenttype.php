<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymenttype extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bookingpayment(){
        return $this->hasMany(Bookingpayment::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }
}
