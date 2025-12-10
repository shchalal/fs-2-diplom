<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\CinemaHall;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CinemaHallValidationTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser()
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    public function test_admin_cannot_create_hall_with_empty_name()
    {
        $response = $this
            ->actingAs($this->adminUser())
            ->post(route('admin.halls.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_can_create_valid_hall()
    {
        $response = $this
            ->actingAs($this->adminUser())
            ->post(route('admin.halls.store'), [
                'name' => 'Зал 1',
            ]);

        $response->assertRedirect(); 

        $this->assertDatabaseHas('cinema_halls', [
            'name' => 'Зал 1',
        ]);
    }
}
