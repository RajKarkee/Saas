<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_item extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'restaurant_id',
        'menu_category_id',
        'name',
        'description',
        'price',
        'is_available',
        'stock_quantity',
    ];

    public function category()
    {
        return $this->belongsTo(Menu_category::class, 'menu_category_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    public function images()
    {
        return $this->hasMany(Menu_item_image::class, 'menu_item_id');
    }
    public function addons()
    {
        return $this->hasMany(Menu_item_addon::class, 'menu_item_id');
}
}
