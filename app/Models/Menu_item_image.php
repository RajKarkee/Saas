<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_item_image extends Model
{
    protected $table = 'menu_item_images';

    protected $fillable = [
        'menu_item_id',
        'image_url',
        'image_alt',
    ];

    public function menuItem()
    {
        return $this->belongsTo(Menu_item::class, 'menu_item_id');
    }
}
