<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\CinemaHall;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition(): array
    {
        return [
            'hall_id'     => CinemaHall::factory(),
            'row_number'         => 1,
            'seat_number' => 1,
            'seat_type'   => 'regular',
        ];
    }
}
