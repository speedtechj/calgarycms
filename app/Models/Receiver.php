<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receiver extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function Sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function receiveraddress()
    {
        return $this->hasMany(Receiveraddress::class);
    }
    protected function myName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }
}
