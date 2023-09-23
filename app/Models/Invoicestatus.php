<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicestatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function trackstatus()
    {
        return $this->belongsTo(Trackstatus::class);
    }
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
    public function provincephil()
    {
        return $this->belongsTo(Provincephil::class);
    }
    public function cityphil()
    {
        return $this->belongsTo(Cityphil::class);
    }
}
