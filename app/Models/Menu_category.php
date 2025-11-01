<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_category extends Model
{
    protected $table = 'menu_categories';

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'position',
        'is_active',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->hasMany(Menu_item::class, 'menu_category_id');
    }
}
