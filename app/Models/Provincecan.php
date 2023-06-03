<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincecan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function citycan(){
        return $this->hasMany(Citycan::class);
    }
    public function user(){
        return $this->hasMany(Provincecan::class);
    }
    public function branch(){
        return $this->hasMany(Provincecan::class);
    }
}
