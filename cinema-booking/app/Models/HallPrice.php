<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HallPrice extends Model
{   
    use HasFactory;
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

