<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPhoto extends Model
{
    protected $table = 'admin__photos';

    protected $fillable = [
        'photo_path', 'admin_id'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
