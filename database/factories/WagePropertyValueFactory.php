<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WagePropertyValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $arCurrencies = [
            'BYN',
            'EUR',
            'USD'
        ];

        return [
            'value' => json_encode([
                'value' => $this->faker->numberBetween(1000, 50000),
                'currency' => $arCurrencies[rand(0, 2)]
            ]),
            'name' => 'ЗП',
            'wage_property_id' => 12,
        ];
    }
}
