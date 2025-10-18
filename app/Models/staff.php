<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class staff extends Model
{
      protected $table = 'staff';

      protected $fillable = [
        'restaurant_id', 'name', 'email', 'password', 'phone', 'role', 'status'
    ];  

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
