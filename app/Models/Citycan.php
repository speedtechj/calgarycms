<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citycan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
    public function user(){
        return $this->hasMany(Citycan::class);
    }
    public function branch(){
        return $this->hasMany(Citycan::class);
    }
}
