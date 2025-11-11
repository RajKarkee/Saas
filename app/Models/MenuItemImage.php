<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemImage extends Model
{
    protected $table = 'menu_item_images';

    protected $fillable = [
        'restaurant_id',
        'menu_item_id',
        'image_url',
        'image_alt',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
