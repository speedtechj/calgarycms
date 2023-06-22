<?php

namespace App\Models;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentdiscount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'discount_amount' => MoneyCast::class,
    ];
    public function servicetype(){
        return $this->belongsTo(Servicetype::class);
    }
    public function boxtype(){
        return $this->belongsTo(Boxtype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
