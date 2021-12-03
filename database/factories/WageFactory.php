<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wage;
use Illuminate\Database\Eloquent\Factories\Factory;

class WageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name'=>$this->faker->name(),
            'user_id'=>User::all()->random()
        ];
    }
}
