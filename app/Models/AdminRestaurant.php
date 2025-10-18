<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRestaurant extends Model
{
    protected $table = 'admin__restaurants';

    protected $fillable = [
        'admin_id', 'restaurant_count'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
