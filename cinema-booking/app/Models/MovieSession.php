<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovieSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'end_time', 
        'price_regular',
        'price_vip',
        'session_date',
    ];
        protected $casts = [
        'session_date' => 'date',
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
