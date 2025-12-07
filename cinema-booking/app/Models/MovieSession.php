<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieSession extends Model
{
    protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'price_regular',
        'price_vip',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'session_id');
    }
}
