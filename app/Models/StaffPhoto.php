<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPhoto extends Model
{
   protected $table = 'staff_photos';

    protected $fillable = [
        'staff_id',
        'photo_url',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
