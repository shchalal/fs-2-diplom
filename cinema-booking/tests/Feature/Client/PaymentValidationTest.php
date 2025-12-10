<?php

namespace Tests\Feature\Client;

use App\Models\CinemaHall;
use App\Models\Movie;
use App\Models\MovieSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_fails_when_session_id_is_missing(): void
    {
        $response = $this->post(route('client.payment.store'), [
      
            'seats' => json_encode([1, 2]),
        ]);

        $response->assertSessionHasErrors('session_id');
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_booking_fails_when_session_id_does_not_exist(): void
    {
        $response = $this->post(route('client.payment.store'), [
            'session_id' => 999999,          
            'seats'      => json_encode([1]),
        ]);

        $response->assertSessionHasErrors('session_id');
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_booking_fails_when_seats_field_is_missing(): void
    {
       
        $hall  = CinemaHall::factory()->create();
        $movie = Movie::factory()->create();

        $session = MovieSession::factory()->create([
            'hall_id'  => $hall->id,
            'movie_id' => $movie->id,
        ]);

        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            
        ]);

        $response->assertSessionHasErrors('seats');
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_booking_fails_when_seats_is_not_valid_json_array(): void
    {
      
        $hall  = CinemaHall::factory()->create();
        $movie = Movie::factory()->create();

        $session = MovieSession::factory()->create([
            'hall_id'  => $hall->id,
            'movie_id' => $movie->id,
        ]);

   
        $response = $this->post(route('client.payment.store'), [
            'session_id' => $session->id,
            'seats'      => 'not-a-json-array',
        ]);

        
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('tickets', 0);
    }
}
