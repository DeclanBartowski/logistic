<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vacancies = Vacancy::all();
        $vacancies->each(function ($item) use ($vacancies) {
            $item->related = $vacancies->where('id','!=',$item->id)->random(3)->pluck('id');
            $item->save();
        });
    }
}
