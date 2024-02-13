<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Storecode;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'total_price' => MoneyCast::class,
        'payment_balance' => MoneyCast::class,
        'extracharge_amount' => MoneyCast::class,
         ];
    protected static function booted()
    {
        
        static::creating(function ($invoice) {

            $lastbooking = Booking::orderBy('booking_invoice', 'desc')->first();
            $invoice->booking_invoice = $lastbooking ? $lastbooking->booking_invoice + 1 : 1;
            $invoice->booking_invoice =  str_pad($invoice->booking_invoice, 7, '0', STR_PAD_LEFT);



            // $invprefix = Storecode::get()->first()->storecode;
            // // Custom invoice number generation logic, e.g., adding a prefix or suffix
            // $lastbooking = Booking::orderBy('booking_invoice', 'desc')->first();
            // // dd($lastbooking->booking_invoice);
            // $invoicetrim = substr($lastbooking->booking_invoice,2);
            // $invoice->booking_invoice = $lastbooking ? intval($invoicetrim) + 1 : 1;
            // $invoice->booking_invoice =   $invprefix.str_pad($invoice->booking_invoice, 7, '0', STR_PAD_LEFT);
        });
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function agentdiscount()
    {
        return $this->belongsTo(Agentdiscount::class);
    }
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function servicetype()
    {
        return $this->belongsTo(Servicetype::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
    public function senderaddress()
    {
        return $this->belongsTo(Senderaddress::class);
    }
    public function receiveraddress()
    {
        return $this->belongsTo(Receiveraddress::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function zoneprice()
    {
        return $this->belongsTo(Zoneprice::class);
    }
    public function agentprice()
    {
        return $this->belongsTo(Agentprice::class);
    }
    public function bookingpayment()
    {
        return $this->hasMany(Bookingpayment::class);
    }
    public function bookingrefund()
    {
        return $this->hasMany(Bookingrefund::class);
    }
    public function packinglist()
    {
        return $this->hasMany(packinglist::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function calculateprice($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $totalinches)
    {
        

        $price = Zoneprice::whereHas('servicetype', function ($query) use ($service_id) {
            $query->where('servicetype_id', $service_id);
        })
            ->whereHas('zone', function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
            ->whereHas('boxtype', function ($query) use ($boxtype_id) {
                $query->where('boxtype_id', $boxtype_id);
            })
            ->whereHas('branch', function ($query) {
                $query->where('branch_id', 1); //default branch id
            })
            ->first();

        if (!$price) {
            return 0;
        }
       
        if ($boxtype_id != 9){
            $totalinches = null;
        }
        $discount = $discount ? Discount::find($discount)->discount_amount : 0;
        $quantity = $boxtype_id ? Boxtype::find($boxtype_id)->total_box : 0;
        $regular_extended_charges = $totalinches ? $totalinches * 6 : 0;
        $irregular_extracharges = ($length && $width && $height) ? $length * $width * $height / 9720 : 0;
        // dump($totalinches);
        if ($boxtype_id != 4) {
            return $price->price  * $quantity + $regular_extended_charges - $discount;
            
        } else {
           
            return $price->price  * $quantity * $irregular_extracharges - $discount;
        }

       
    }
    public function agentprices($service_id, $zone_id, $boxtype_id, $discount, $length, $width, $height, $agent_id, $totalinches)
    {
       
        $price = Agentprice::with(['servicetype', 'zone', 'boxtype', 'agent'])
            ->whereHas('servicetype', function ($query) use ($service_id) {
                $query->where('servicetype_id', $service_id);
            })
            ->whereHas('zone', function ($query) use ($zone_id) {
                $query->where('zone_id', $zone_id);
            })
            ->whereHas('boxtype', function ($query) use ($boxtype_id) {
                $query->where('boxtype_id', $boxtype_id);
            })
            ->whereHas('agent', function ($query) use ($agent_id) {
                $query->where('agent_id', $agent_id);
            })
            ->first();
            
        if ($price == null) {
            return 0;
        }
        if ($boxtype_id != 9){
            $totalinches = null;
        }
        if($discount == null){
            $discount = 0;
        }
        // Only calculate these when needed
        $regular_extended_charges = $totalinches ? $totalinches * 6 : 0;
        $irregular_extracharges = ($length && $width && $height) ? $length * $width * $height  / 9720 : 0;
        $discount = $discount ? Agentdiscount::find($discount)->discount_amount : 0;
        $quantity = $boxtype_id ? Boxtype::find($boxtype_id)->total_box : 0;

        if ($boxtype_id != 4) {
            return $price->price * $quantity + $regular_extended_charges - $discount;
        } else {
            return $price->price * $quantity * $irregular_extracharges - $discount;
        }
        
    }
}
