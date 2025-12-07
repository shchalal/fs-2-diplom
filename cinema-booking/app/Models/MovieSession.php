<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_id',
        'movie_id',
        'start_time',
        'end_time',
        'price_regular',
        'price_vip',
    ];

    public function hall()
    {
        return $this->belongsTo(CinemaHall::class, 'hall_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}

