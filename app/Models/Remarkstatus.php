<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remarkstatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
       
        'invoicedoc' => 'array',
        'assign_to' => 'array',
       
        
    ];
    const STATUS = [
        'Open' => 'Open',
        'Closed' => 'Closed',
    ];
    protected static function booted()
{
    static::creating(function ($remarkticket) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastticket = Remarkstatus::orderBy('ticket_number', 'desc')->first();
        $remarkticket->ticket_number = $lastticket ? $lastticket->ticket_number + 1 : 1;
        $remarkticket->ticket_number =  str_pad($remarkticket->ticket_number, 7, '0', STR_PAD_LEFT);
    });
}
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function statuscategory()
    {
        return $this->belongsTo(Statuscategory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assignby()
    {
        return $this->belongsTo(User::class, 'assign_by');
    }
    public function assignto()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
    public function searchinvoice()
    {
        return $this->belongsTo(Searchinvoice::class, 'booking_id');
    }
}
