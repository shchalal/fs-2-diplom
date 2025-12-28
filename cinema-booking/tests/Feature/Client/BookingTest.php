<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\HallPrice;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_book_tickets_and_qr_codes_are_generated()
    {
        Storage::fake('public');

        $date = now()->toDateString();

        $hall = CinemaHall::factory()->create([
            'rows' => 5,
            'seats_per_row' => 8,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        $seats = Seat::where('hall_id', $hall->id)->take(2)->get();
        $seatIds = $seats->pluck('id')->toArray();

        HallPrice::factory()->create([
            'hall_id' => $hall->id,
            'regular_price' => 300,
            'vip_price' => 600,
        ]);

        $movie = Movie::factory()->create();

        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => Carbon::now()->addDay()->setTime(18, 30)->format('H:i:s'),
            'end_time'   => Carbon::now()->addDay()->setTime(20, 30)->format('H:i:s'),
            'price_regular' => 300,
            'price_vip' => 600,
        ]);

        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats'      => json_encode($seatIds),
            'date'       => $date,
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('/ticket/', $response->getTargetUrl());

        $this->assertDatabaseCount('tickets', 2);

        $tickets = Ticket::all();
        $orderId = $tickets->first()->order_id;

        $this->assertTrue(
            $tickets->every(fn ($ticket) => $ticket->order_id === $orderId)
        );

        foreach ($tickets as $ticket) {
            Storage::disk('public')->assertExists($ticket->qr_path);
        }
    }

    public function test_seat_cannot_be_double_booked()
    {
        Storage::fake('public');

        $date = now()->toDateString();

        $hall = CinemaHall::factory()->create([
            'rows' => 3,
            'seats_per_row' => 5,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        $seat = Seat::where('hall_id', $hall->id)->first();

        $movie = Movie::factory()->create();

        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
        ]);

        Ticket::factory()->create([
            'session_id'  => $session->id,
            'seat_id'     => $seat->id,
            'ticket_date' => $date,
        ]);

        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats'      => json_encode([$seat->id]),
            'date'       => $date,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('tickets', 1);
    }
}
