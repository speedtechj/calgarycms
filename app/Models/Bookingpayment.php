<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookingpayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function paymenttype()
    {
        return $this->belongsTo(Paymenttype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    
}
