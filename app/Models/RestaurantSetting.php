<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    protected $table = 'restaurant_settings';

    protected $fillable = [
        'restaurant_id', 'logo', 'address', 'phone', 'email', 'map_url'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
