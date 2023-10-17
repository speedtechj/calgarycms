<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuscategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function remarkstatus(){
        return $this->belongsTo(Remarkstatus::class);
    }
}
