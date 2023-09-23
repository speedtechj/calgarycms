<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trackstatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function invoicestatus(){
        return $this->hasMany(Invoicestatus::class);
    }
}
