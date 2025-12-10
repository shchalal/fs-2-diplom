<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_halls_index_to_login(): void
    {
        $response = $this->get(route('admin.halls.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_movies_index_to_login(): void
    {
        $response = $this->get(route('admin.movies.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_sessions_index_to_login(): void
    {
        $response = $this->get(route('admin.sessions.index'));

        $response->assertRedirect(route('login'));
    }
}
