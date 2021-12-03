<?php

namespace App\Providers;

use App\Models\Seo;
use App\Models\WageProperty;
use App\Services\SiteSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function getMeta()
    {
        \SEOMeta::setTitle('Зарплаты в логистике | LogisticSalaries');
        \SEOMeta::setDescription('Первый сервис зарплат по логистике в Беларуси. С помощью LogisticSalaries вы можете следить за зарплатами в логистике по разным специализациям');
        \SEOMeta::addKeyword(['логист зарплата, сколько зарабатывает логист, логистика зарплата, сколько зарабатывают дальнобойщики']);

        $currentPage = $this->app->request->getRequestUri();
        $seo = Seo::where('link', '=', $currentPage)->first();
        if (!empty($seo)) {
            \SEOMeta::setTitle($seo->title);
            \SEOMeta::setDescription($seo->description);
            \SEOMeta::addKeyword([$seo->keywords]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('SiteSetting', SiteSettings::class);
        Paginator::defaultView('pagination');
        WageProperty::Saved(function ($item) {
            $item->load('values');
            if ($item->getRelation('values')) {
                $item->getRelation('values')->each(function ($valueItem) use ($item) {
                    if ($valueItem->name != $item->name) {
                        $valueItem->name = $item->name;
                        $valueItem->save();
                    }
                });
            }
        });
        $this->getMeta();
    }
}
