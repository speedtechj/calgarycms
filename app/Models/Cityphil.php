<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cityphil extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function provincephil(){
        return $this->belongsTo(Provincephil::class);
    }
    public function barangayphil(){
        return $this->hasMany(Barangayphil::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
}
