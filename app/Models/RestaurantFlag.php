<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantFlag extends Model
{
    //
    protected $table = 'restaurant_flags';
    protected $fillable = [
        'restaurant_id', 'flag_value'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
