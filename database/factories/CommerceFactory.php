<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommerceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'active'=>1,
            'active_from'=>$this->faker->dateTimeBetween('-1 year'),
            'active_to'=>$this->faker->dateTimeBetween('now','+10 years'),
            'picture'=>'img/article.png',
            'link'=>$this->faker->url()
        ];
    }
}
