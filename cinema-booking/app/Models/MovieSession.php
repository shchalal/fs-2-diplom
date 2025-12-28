<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Seat;
use Illuminate\Support\Collection;

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
    ];

  
    protected $casts = [];

    
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

  
    public function getStartTimeFormattedAttribute(): string
    {
        return substr($this->start_time, 0, 5);
    }

    
    public function getEndTimeFormattedAttribute(): string
    {
        return substr($this->end_time, 0, 5);
    }
    public function priceForSeat(Seat $seat): int
    {
        if ($seat->seat_type === 'vip') {
            $price = $this->price_vip > 0
                ? $this->price_vip
                : optional($this->hall->price)->vip_price;
        } else {
            $price = $this->price_regular > 0
                ? $this->price_regular
                : optional($this->hall->price)->regular_price;
        }

        return $price ?? 0;
    }

    public function totalPriceForSeats(Collection $seats): int
    {
        return $seats->sum(fn (Seat $seat) => $this->priceForSeat($seat));
    }
}
