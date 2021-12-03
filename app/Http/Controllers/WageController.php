<?php

namespace App\Http\Controllers;

use App\Http\Requests\WageRequest;
use App\Models\Wage;
use App\Services\Salary;
use App\Services\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WageController extends Controller
{
    /**
     * @param Salary $salaryService
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Salary $salaryService, SiteSettings $siteSettings)
    {
        $settings = $siteSettings->getSiteSettings();
        $form = $salaryService->getFilterForm();
        $count = Wage::count();
        return view('salary.index', compact('form', 'count', 'settings'));
    }

    /**
     * @param Request $request
     * @param Salary $salaryService
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filterResult(Request $request, Salary $salaryService, SiteSettings $siteSettings)
    {
        $arStatistic = $salaryService->getStatistic($request->input('properties'));
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

    /**
     * @param Salary $salaryService
     * @param SiteSettings $siteSettings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function self(Salary $salaryService, SiteSettings $siteSettings)
    {
        if (Auth::check()) {
            $arStatistic = $salaryService->getSelfStatistic();
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

        } else {
            return redirect()->route('home');
        }
    }

    /**
     * @param Salary $salary
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Salary $salary)
    {
        $form = $salary->getAddForm();
        if (Auth::check()) {
            $userWage = Auth::user()->load('wage')->getRelation('wage');
        } else {
            $userWage = null;
        }

        return view('salary.create', compact('form', 'userWage'));
    }

    /**
     * @param WageRequest $request
     * @param Salary $salary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WageRequest $request, Salary $salary)
    {
        return $salary->saveWage($request->validated());
    }

    public function suggestion(Salary $salary, Request $request)
    {
        return $salary->getCitySuggestion($request->input('query'));
    }
}
