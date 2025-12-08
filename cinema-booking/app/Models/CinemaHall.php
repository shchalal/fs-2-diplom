<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seat;
use App\Models\MovieSession;
use App\Models\HallPrice;

class CinemaHall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rows',
        'seats_per_row',
        'is_active',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'hall_id');
    }

    public function sessions()
    {
        return $this->hasMany(MovieSession::class, 'hall_id');
    }

    public function price()
    {
        return $this->hasOne(HallPrice::class, 'hall_id');
    }

    
    public function generateSeats(): void
    {
        for ($row = 1; $row <= $this->rows; $row++) {
            for ($seat = 1; $seat <= $this->seats_per_row; $seat++) {

                $type = ($row > $this->rows - 2)
                    ? 'vip'
                    : 'regular';

                Seat::create([
                    'hall_id'     => $this->id,
                    'row_number'  => $row,
                    'seat_number' => $seat,
                    'seat_type'   => $type,
                ]);
            }
        }
    }
}

