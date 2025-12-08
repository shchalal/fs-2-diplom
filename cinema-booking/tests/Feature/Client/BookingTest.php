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

    /** @test */
    public function guest_can_book_tickets_and_qr_codes_are_generated()
    {
        Storage::fake('public');

        // 1. Создание зала с местами
        $hall = CinemaHall::factory()->create([
            'rows' => 5,
            'seats_per_row' => 8,
            'is_active' => true,
        ]);

        $hall->generateSeats();
        $seats = Seat::where('hall_id', $hall->id)->take(2)->get();

        // 2. Цены зала
        HallPrice::factory()->create([
            'hall_id' => $hall->id,
            'regular_price' => 300,
            'vip_price' => 600,
        ]);

        // 3. Фильм и сеанс
        $movie = Movie::factory()->create();

        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => Carbon::now()->addDay()->setTime(18, 30),
            'end_time' => Carbon::now()->addDay()->setTime(20, 30),
            'price_regular' => 300,
            'price_vip' => 600,
        ]);

        $seatIds = $seats->pluck('id')->toArray();

        // 4. Имитация POST /payment
        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats' => json_encode($seatIds),
        ]);

        // 5. Проверка redirect на /ticket/{order}
        $response->assertRedirect();
        $redirect = $response->getTargetUrl();
        $this->assertStringContainsString('/ticket/', $redirect);

        // 6. Проверка, что создалось 2 билета
        $this->assertDatabaseCount('tickets', 2);

        $tickets = Ticket::all();
        $orderId = $tickets->first()->order_id;

        // у всех билетов один order
        $this->assertTrue($tickets->every(fn($t) => $t->order_id === $orderId));

        // 7. Проверка существования QR-файлов
        foreach ($tickets as $ticket) {
            Storage::disk('public')->assertExists($ticket->qr_path);
        }
    }

    /** @test */
    public function seat_cannot_be_double_booked()
    {
        Storage::fake('public');

        
        $hall = CinemaHall::factory()->create(['rows' => 3, 'seats_per_row' => 5, 'is_active' => true]);
        $hall->generateSeats();

        $seat = Seat::where('hall_id', $hall->id)->first();

        $movie = Movie::factory()->create();
        $session = MovieSession::factory()->create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
        ]);

     
        Ticket::factory()->create([
            'session_id' => $session->id,
            'seat_id' => $seat->id,
        ]);

       
        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats' => json_encode([$seat->id]),
        ]);

        $response->assertSessionHasErrors();

        
        $this->assertDatabaseCount('tickets', 1);
    }
}
