<?php

namespace Database\Seeders;

use App\Models\Wage;
use App\Models\WageProperty;
use App\Models\WagePropertyValue;
use Illuminate\Database\Seeder;

class WageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arCurrencies = [
            'BYN',
            'EUR',
            'USD'
        ];
        $faker = \Faker\Factory::create();
        $arPrices = [11,12];
        $properties = WageProperty::where('active', 1)->with('options')->get();
        $wages = Wage::all();
        foreach ($wages as $wage){
            $wage->load('properties');
            $arProperties = [];
            foreach ($properties as  $property) {
                $property->load('options');
                if(in_array($property->id,$arPrices)){
                    $value = json_encode([
                        'value' => $faker->numberBetween(1000, 50000),
                        'currency' => $arCurrencies[rand(0, 2)]
                    ]);
                }elseif ($property->id == 7 ){
                    $value = 'Беларусь, Гомельская обл, г Гомель';
                }else{
                    $value = $property->getRelation('options')->random();
                }

                    if (is_array($property)) {
                        $property = json_encode($property);
                    }

                    $arProperties[] = WagePropertyValue::updateOrCreate([
                        'wage_id' => $wage->id,
                        'wage_property_id' => $property->id
                    ], [
                        'wage_property_id' => $property->id,
                        'wage_property_option_id' => $value->id??null,
                        'value' => $value->value??$value,
                        'name' => $property->name,
                    ]);

            };

            if ($arProperties) {
                $wage->properties()->saveMany($arProperties);
            }
        }



    }
}
