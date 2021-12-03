<?php

namespace Database\Seeders;

use App\Models\Commerce;
use App\Models\Publication;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Wage;
use App\Models\WagePropertyValue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Sponsor::factory(5)->create();
        Vacancy::factory(27)->create();
        Commerce::factory(3)->create();
        Publication::factory(27)->create();
        $this->call(SiteSettingsSeeder::class);
        $this->call(VacancySeeder::class);
        $this->call(MenuItemSeeder::class);
        $this->call(HeaderBannerSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(TextPageSeeder::class);
        $this->call(WagePropertySeeder::class);
        Wage::factory(150)->has(WagePropertyValue::factory()->count(1), 'properties')->create();
        $this->call(WageSeeder::class);

    }
}
