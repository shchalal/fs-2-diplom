<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\HallPrice;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCostTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_page_calculates_total_cost_correctly()
    {
        $date = now()->toDateString();

   
        $hall = CinemaHall::factory()->create([
            'rows' => 4,
            'seats_per_row' => 4,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        HallPrice::factory()->create([
            'hall_id' => $hall->id,
            'regular_price' => 300,
            'vip_price' => 600,
        ]);

   
        $movie = Movie::factory()->create();
        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => '18:00:00',
            'end_time'   => '20:00:00',
        ]);

       
        $regularSeat = Seat::where('seat_type', 'regular')->first();
        $vipSeat     = Seat::where('seat_type', 'vip')->first();

        $seatIds = [$regularSeat->id, $vipSeat->id];

      
        $response = $this->get(route('client.payment', [
            'session_id' => $session->id,
            'seats'      => json_encode($seatIds),
            'date'       => $date,
        ]));

        $response->assertOk();

       
        $response->assertSee('900');
    }
}
