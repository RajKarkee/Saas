<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_item_addon extends Model
{
    protected $table = 'menu_item_addons';

    protected $fillable = [
        'restaurant_id',

        'menu_item_id',
        'name',
        'additional_price',
        'is_available',
        'max_select',
    ];

    public function menuItem()
    {
        return $this->belongsTo(Menu_Item::class, 'menu_item_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    
}
