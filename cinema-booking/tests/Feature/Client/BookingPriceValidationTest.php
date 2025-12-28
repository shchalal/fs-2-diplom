<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingPriceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_fails_when_total_price_is_zero()
    {
        Storage::fake('public');

        $date = now()->toDateString();

        $hall = CinemaHall::factory()->create([
            'rows' => 2,
            'seats_per_row' => 2,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        $seat = Seat::where('hall_id', $hall->id)->first();

        $movie = Movie::factory()->create();

        
        $session = MovieSession::factory()->create([
            'hall_id'        => $hall->id,
            'movie_id'       => $movie->id,
            'price_regular'  => 0,
            'price_vip'      => 0,
            'start_time'     => '18:00:00',
            'end_time'       => '20:00:00',
        ]);

        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats'      => json_encode([$seat->id]),
            'date'       => $date,
        ]);

        $response->assertSessionHasErrors('price');
        $this->assertDatabaseCount('tickets', 0);
    }
}
