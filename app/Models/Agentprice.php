<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentprice extends Model
{
    use HasFactory;
    protected $casts = [
        'price' => MoneyCast::class,
    ];
    protected $guarded = [];
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
}
