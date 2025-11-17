<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Staff extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'staff';

    protected $fillable = [
        'restaurant_id', 'name', 'email', 'password', 'phone', 'role', 'status'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function photo()
    {
        return $this->hasOne(StaffPhoto::class);
    }
    public function getRoleNameAttribute(){
        return match($this->role){
            0=>'Manager',
            1=>'Staff',
            2=>'Delivery',
            default=>'Unknown',
        };
    }
}
