<?php

namespace App\Http\Controllers;


use App\Services\Salary;
use App\Services\SiteSettings;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{

    /**
     * @param Salary $salaryService
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke(Salary $salaryService, SiteSettings $siteSettings)
    {
        $arStatistic = $salaryService->getStatistic();
        $settings = $siteSettings->getSiteSettings();
        if (isset($settings->texts['main_form_salary']) && $settings->texts['main_form_salary']) {
            $settings->main_form_salary = str_replace('{form_quantity}',
                trans_choice('wages.form_quantity', $arStatistic['count'], ['count' => $arStatistic['count']]),
                $settings->texts['main_form_salary']);
        }
        if (isset($settings->texts['main_form_salary_error']) && $settings->texts['main_form_salary_error']) {
            $settings->main_form_salary_error = str_replace('{form_quantity}',
                trans_choice('wages.form_quantity', $arStatistic['count'], ['count' => $arStatistic['count']]),
                $settings->texts['main_form_salary_error']);
        }
        if (Auth::check()) {
            $userWage = Auth::user()->load('wage')->getRelation('wage');
        } else {
            $userWage = null;
        }
        return view('index', compact('arStatistic', 'settings', 'userWage'));
    }

}
