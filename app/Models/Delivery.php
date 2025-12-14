<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'deliveries';

    protected $fillable = [
        'order_id',
        'delivery_person_id',
        'is_seen',
        
        'assigned_at',
    ];
}
