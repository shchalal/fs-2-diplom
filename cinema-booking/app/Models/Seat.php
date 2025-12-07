<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'hall_id',
        'row_number',
        'seat_number',
        'seat_type',
    ];

    public function hall()
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }
}
