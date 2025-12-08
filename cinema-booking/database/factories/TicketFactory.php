<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\MovieSession;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'session_id'   => MovieSession::factory(),
            'seat_id'      => Seat::factory(),
            'order_id'     => Str::upper(Str::random(10)),
            'booking_code' => Str::upper(Str::random(8)),
            'qr_path'      => 'qr/' . Str::upper(Str::random(8)) . '.png',
        ];
    }
}
