<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        
        $ageLimits = ['0+', '6+', '12+', '16+', '18+'];

        return [
            'title'       => $this->faker->randomElement([
                'Интерстеллар',
                'Начало',
                'Матрица',
                'Тёмный рыцарь',
                'Звёздные войны: Пробуждение силы',
                'Гарри Поттер и философский камень',
                'Человек-паук: Через вселенные',
                'Аватар',
                'Дюна',
                'Мстители: Финал',
            ]),

            'description' => $this->faker->paragraphs(3, true),

           
            'poster_url'  => 'posters/default.jpg',

         
            'duration'    => $this->faker->numberBetween(80, 180),

            'age_limit'   => $this->faker->randomElement($ageLimits),
        ];
    }
}
