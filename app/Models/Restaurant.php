<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /**
     * The table associated with the model.
     * Laravel would default to 'restaurants', so this is explicit for clarity.
     *
     * @var string
     */
    protected $table = 'restaurants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'owner_id', 'domain', 'subdomain', 'logo', 'status'
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
    // Add any relationships or scopes here if needed later.
    public function owner()
    {
        return $this->belongsTo(Admin::class, 'owner_id');
    }
    public function settings()
    {
        return $this->hasOne(RestaurantSetting::class);
    }
    public function menuCategories()
    {
        return $this->hasMany(Menu_category::class);
    }
    public function menuItems()
    {
        return $this->hasMany(Menu_item::class);
    }
    public function menuItemAddons()
    {
        return $this->hasManyThrough(Menu_item_addon::class, Menu_item::class, 'restaurant_id', 'menu_item_id', 'id', 'id');
    }
}
