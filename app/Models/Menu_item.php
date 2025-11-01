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
}
