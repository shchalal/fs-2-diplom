<?php

namespace Database\Factories;

use App\Models\HallPrice;
use App\Models\CinemaHall;
use Illuminate\Database\Eloquent\Factories\Factory;

class HallPriceFactory extends Factory
{
    protected $model = HallPrice::class;

    public function definition()
    {
        return [
            'hall_id'       => CinemaHall::factory(),
            'regular_price' => $this->faker->numberBetween(200, 500),
            'vip_price'     => $this->faker->numberBetween(400, 900),
        ];
    }
}


