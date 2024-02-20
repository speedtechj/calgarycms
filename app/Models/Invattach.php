<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invattach extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
       
        'invattachment' => 'array',
        // 'assign_to' => 'array',
    ];
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
