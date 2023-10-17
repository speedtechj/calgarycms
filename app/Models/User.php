<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements  FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'file_doc' => 'array',
    ];

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function canAccessFilament(): bool
    {
        return str_ends_with($this->is_active, true);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
    public function sender(){
        return $this->hasMany(Sender::class);
    }
    public function batch(){
        return $this->hasMany(Batch::class);
    }
    // public function discount(){
    //     return $this->belongsTo(Discount::class);
    // }

    // public function calculateDiscount()
    // {
    //     dd('test');
    // }
}
