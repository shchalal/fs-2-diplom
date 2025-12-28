<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCostZeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_cost_is_zero_when_no_prices_defined()
    {
        $date = now()->toDateString();

        $hall = CinemaHall::factory()->create([
            'rows' => 2,
            'seats_per_row' => 2,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        $seat = Seat::first();

       
        $movie = Movie::factory()->create();
        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => '18:00:00',
            'end_time'   => '20:00:00',
        ]);

        $response = $this->get(route('client.payment', [
            'session_id' => $session->id,
            'seats'      => json_encode([$seat->id]),
            'date'       => $date,
        ]));

        $response->assertOk();

    
        $response->assertSee('0');
    }
}
