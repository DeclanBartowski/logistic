<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * @param \App\Services\Vacancy $vacancyService
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(\App\Services\Vacancy $vacancyService, Request $request)
    {
        $query = $request->input('q');
        $vacancies = $vacancyService->getVacancies($query);

        return view('vacancies.index', compact('vacancies', 'query'));
    }


    /**
     * @param Vacancy $vacancy
     * @param \App\Services\Vacancy $vacancyService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Vacancy $vacancy, \App\Services\Vacancy $vacancyService)
    {
        $vacancyService->getDetail($vacancy);
        return view('vacancies.detail', compact('vacancy'));
    }

}
