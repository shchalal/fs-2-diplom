<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallPrice extends Model
{
    protected $fillable = [
        'hall_id',
        'regular_price',
        'vip_price',
    ];

    public function hall()
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }
}

