<?php

namespace Database\Factories;

use App\Models\CinemaHall;
use Illuminate\Database\Eloquent\Factories\Factory;

class CinemaHallFactory extends Factory
{
    protected $model = CinemaHall::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Зал 1',
                'Зал 2',
                'Большой зал',
                'VIP-зал',
                'IMAX',
            ]),

           
            'rows'          => $this->faker->numberBetween(5, 12),
            'seats_per_row' => $this->faker->numberBetween(6, 16),

            
            'is_active'     => false,
        ];
    }
}
