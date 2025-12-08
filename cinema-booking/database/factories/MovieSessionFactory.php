<?php

namespace Database\Factories;

use App\Models\MovieSession;
use App\Models\CinemaHall;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieSessionFactory extends Factory
{
    protected $model = MovieSession::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+2 days');
        $end   = (clone $start)->modify('+2 hours');

        return [
            'hall_id'       => CinemaHall::factory(),
            'movie_id'      => Movie::factory(),
            'start_time'    => $start,
            'end_time'      => $end,
            'price_regular' => $this->faker->numberBetween(200, 500),
            'price_vip'     => $this->faker->numberBetween(400, 900),
        ];
    }
}
