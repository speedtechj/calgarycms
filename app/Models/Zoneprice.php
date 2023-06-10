<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;

class Zoneprice extends Model
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
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
}
