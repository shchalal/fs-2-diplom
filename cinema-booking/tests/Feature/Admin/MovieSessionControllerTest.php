<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Movie;
use App\Models\CinemaHall;
use App\Models\MovieSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => 1,
        ]);
    }


    public function admin_can_view_sessions_index()
    {
        $this->actingAs($this->admin);

   
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

 
    public function admin_cannot_create_session_with_invalid_data()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.sessions.store'), [
            'movie_id'   => null,
            'hall_id'    => null,
            'start_time' => null,
        ]);

        $response->assertSessionHasErrors([
            'movie_id',
            'hall_id',
            'start_time',
        ]);
    }


    public function admin_can_create_session_with_valid_data()
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create(['duration' => 120]);
        $hall  = CinemaHall::factory()->create(['is_active' => 1]);

        $response = $this->post(route('admin.sessions.store'), [
            'movie_id'   => $movie->id,
            'hall_id'    => $hall->id,
            'start_time' => '15:00',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('movie_sessions', [
            'movie_id'   => $movie->id,
            'hall_id'    => $hall->id,
            'start_time' => '15:00:00',
            'end_time'   => '17:00:00', 
        ]);
    }

   
    public function admin_can_update_session()
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create(['duration' => 90]);
        $hall  = CinemaHall::factory()->create(['is_active' => 1]);

        $session = MovieSession::create([
            'movie_id'       => $movie->id,
            'hall_id'        => $hall->id,
            'start_time'     => '10:00:00',
            'end_time'       => '11:30:00',
            'price_regular'  => 0,
            'price_vip'      => 0,
        ]);

        $response = $this->put(route('admin.sessions.update', $session->id), [
            'movie_id'   => $movie->id,
            'hall_id'    => $hall->id,
            'start_time' => '18:30',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('movie_sessions', [
            'id'         => $session->id,
            'start_time' => '18:30:00',
            'end_time'   => '20:00:00', 
        ]);
    }

  
    public function admin_can_delete_session()
    {
        $this->actingAs($this->admin);

        $session = MovieSession::create([
            'movie_id'       => Movie::factory()->create()->id,
            'hall_id'        => CinemaHall::factory()->create()->id,
            'start_time'     => '12:00:00',
            'end_time'       => '14:00:00',
            'price_regular'  => 0,
            'price_vip'      => 0,
        ]);

        $response = $this->delete(route('admin.sessions.destroy', $session->id));

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseMissing('movie_sessions', [
            'id' => $session->id,
        ]);
    }
}
