<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class Branch extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted()
{
    static::creating(function ($branchid) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastbranchid = Branch::orderBy('branchid', 'desc')->first();
        $branchid->branchid = $lastbranchid ? $lastbranchid->branchid + 1 : 1;
        $branchid->branchid =  str_pad($branchid->branchid, 6, '0', STR_PAD_LEFT);
    });
}
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'file_doc' => 'array',
    ];
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
    public function sender(){
        return $this->belongsTo(Sender::class);
    }

}
