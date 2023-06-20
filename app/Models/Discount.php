<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function servicetype(){
        return $this->belongsTo(Servicetype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }

}
