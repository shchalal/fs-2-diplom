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

    /** @test */
    public function admin_can_view_sessions_index()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.sessions.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_cannot_create_session_with_invalid_data()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.sessions.store'), [
            'movie_id'    => null,
            'hall_id'     => null,
            'session_date'=> null,
            'start_time'  => null,
        ]);

        $response->assertSessionHasErrors([
            'movie_id',
            'hall_id',
            'start_time',
        ]);
    }

    /** @test */
    public function admin_can_create_session_with_valid_data()
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create();
        $hall  = CinemaHall::factory()->create(['is_active' => 1]);

        $response = $this->post(route('admin.sessions.store'), [
            'movie_id' => $movie->id,
            'hall_id'  => $hall->id,
            'start_time' => '15:00',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

     
        $sessionDate = now()->addDay()->format('Y-m-d');
        $startDateTime = now()->addDay()->setTime(15, 0)->format('Y-m-d H:i:s');

        $this->assertDatabaseHas('movie_sessions', [
            'movie_id'     => $movie->id,
            'hall_id'      => $hall->id,
            'session_date' => $sessionDate . ' 00:00:00',
            'start_time'   => $startDateTime,
        ]);
    }

    /** @test */
    public function admin_can_update_session()
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create();
        $hall  = CinemaHall::factory()->create(['is_active' => 1]);

        $session = MovieSession::factory()->create([
            'movie_id'     => $movie->id,
            'hall_id'      => $hall->id,
            'session_date' => now()->format('Y-m-d'),
            'start_time'   => now()->setTime(10, 0),
        ]);

        $response = $this->put(route('admin.sessions.update', $session->id), [
            'movie_id'     => $movie->id,
            'hall_id'      => $hall->id,
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time'   => '18:30',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('movie_sessions', [
            'id'            => $session->id,
            'session_date'  => now()->addDays(2)->format('Y-m-d') . ' 00:00:00',
            'start_time'    => now()->addDays(2)->setTime(18, 30)->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test */
    public function admin_can_delete_session()
    {
        $this->actingAs($this->admin);

        $session = MovieSession::factory()->create();

        $response = $this->delete(route('admin.sessions.destroy', $session->id));

     
        $response->assertRedirect('/');

        $this->assertDatabaseMissing('movie_sessions', [
            'id' => $session->id,
        ]);
    }
}
