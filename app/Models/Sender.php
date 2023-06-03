<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function User(){
        return $this->belongsTo(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function senderaddress(){
        return $this->hasMany(Senderaddress::class);
    }
    public function receiver(){
        return $this->hasMany(Receiver::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }
    public function bookingpayment(){
        return $this->hasMany(Bookingpayment::class);
    }
    public function bookingrefund(){
        return $this->hasMany(Bookingrefund::class);
    }
    public function packinglist(){
        return $this->hasMany(Packinglist::class);
    }

}
