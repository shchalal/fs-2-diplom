<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HallTakenSeatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_hall_shows_taken_seats_only_for_selected_date()
    {
        $hall = CinemaHall::factory()->create([
            'rows' => 2,
            'seats_per_row' => 2,
            'is_active' => true,
        ]);
        $hall->generateSeats();

        $seat = Seat::where('hall_id', $hall->id)->first();

        $movie = Movie::factory()->create(['duration' => 120]);

        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => '18:30:00',
            'end_time'   => '20:30:00',
        ]);

        $dateA = now()->toDateString();
        $dateB = now()->addDay()->toDateString();

        Ticket::factory()->create([
            'session_id'  => $session->id,
            'seat_id'     => $seat->id,
            'ticket_date' => $dateA,
        ]);

        $rA = $this->get(route('client.hall', [
            'session' => $session->id,
            'date'    => $dateA,
        ]));
        $rA->assertOk();
        $rA->assertViewHas('takenSeats', fn ($taken) =>
            in_array($seat->id, $taken)
        );

        $rB = $this->get(route('client.hall', [
            'session' => $session->id,
            'date'    => $dateB,
        ]));
        $rB->assertOk();
        $rB->assertViewHas('takenSeats', fn ($taken) =>
            !in_array($seat->id, $taken)
        );
    }
}
