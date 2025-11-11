<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSchedule extends Model
{
    protected $table = 'restaurant_schedules_tables';
    protected $fillable = [
        'restaurant_id', 'day_of_week', 'opening_time', 'closing_time', 'is_open'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
