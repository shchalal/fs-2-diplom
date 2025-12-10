<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_view_movies_index(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.movies.index'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_create_movie_without_title(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.movies.store'), [
            'title'       => '',
            'description' => 'Описание фильма',
            'duration'    => 120,
            'age_limit'   => '16+',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_admin_cannot_create_movie_with_invalid_duration(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.movies.store'), [
            'title'       => 'Фильм без нормальной длительности',
            'description' => 'Описание фильма',
            'duration'    => 0, 
            'age_limit'   => '16+',
        ]);

        $response->assertSessionHasErrors(['duration']);
    }

    public function test_admin_can_create_movie_with_valid_data(): void
    {
        $this->actingAs($this->admin);

        $data = [
            'title'       => 'Интерстеллар',
            'description' => 'Фантастический фильм о космосе и времени',
            'duration'    => 169,
            'age_limit'   => '12+',
            'poster_url'  => 'https://example.com/interstellar.jpg',
        ];

        $response = $this->post(route('admin.movies.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('movies', [
            'title'    => 'Интерстеллар',
            'duration' => 169,
        ]);
    }

    public function test_admin_can_update_movie(): void
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create([
            'title'    => 'Старое название',
            'duration' => 100,
        ]);

        $response = $this->put(route('admin.movies.update', $movie), [
            'title'       => 'Новое название',
            'description' => $movie->description,
            'duration'    => 120,
            'age_limit'   => $movie->age_limit,
            'poster_url'  => $movie->poster_url,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('movies', [
            'id'       => $movie->id,
            'title'    => 'Новое название',
            'duration' => 120,
        ]);
    }

    public function test_admin_can_delete_movie(): void
    {
        $this->actingAs($this->admin);

        $movie = Movie::factory()->create();

        $response = $this->delete(route('admin.movies.destroy', $movie));

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('movies', [
            'id' => $movie->id,
        ]);
    }
}
