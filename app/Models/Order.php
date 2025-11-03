<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'customer_id', 'restaurant_id', 'total_amount', 'order_type', 'status','delivery_time','payment_method','notes','order_date'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class, 'order_id');
    }
}
