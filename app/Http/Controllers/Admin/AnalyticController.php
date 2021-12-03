<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AnalyticSalary;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{

    /**
     * @param AnalyticSalary $analyticSalary
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke(AnalyticSalary $analyticSalary)
    {
        $filter = $analyticSalary->getAnalyticForm();
        $arStatistic = $analyticSalary->getAnalytic();
        $wageProperties = $analyticSalary->getWagesProperties();

        return view('admin.analytic.index', [
            'filter' => $filter,
            'wage' => $analyticSalary->wagePropertyId,
            'arStatistic' => $arStatistic['statistic'],
            'items' => $arStatistic['items'],
            'wageProperties' => $wageProperties
        ]);
    }

    /**
     * @param AnalyticSalary $analyticSalary
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function compare(AnalyticSalary $analyticSalary)
    {
        $filter = $analyticSalary->getAnalyticForm();
        $wageProperties = $analyticSalary->getWagesProperties();
        return view('admin.analytic.compare', [
            'filter' => $filter,
            'wage' => $analyticSalary->wagePropertyId,
            'wageProperties' => $wageProperties
        ]);
    }

    /**
     * @param AnalyticSalary $analyticSalary
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getAnalytic(AnalyticSalary $analyticSalary, Request $request)
    {
        $filter = $analyticSalary->getAnalyticForm($request->all());
        $arStatistic = $analyticSalary->getAnalytic($request->all(), $request->input('wage', 0));
        $wageProperties = $analyticSalary->getWagesProperties();

        return view('admin.analytic.index', [
            'filter' => $filter,
            'wage' => $analyticSalary->wagePropertyId,
            'arStatistic' => $arStatistic['statistic'],
            'items' => $arStatistic['items'],
            'wageProperties' => $wageProperties
        ]);
    }

    /**
     * @param AnalyticSalary $analyticSalary
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getCompareAnalytic(AnalyticSalary $analyticSalary, Request $request)
    {

        $arStatistic = $analyticSalary->getCompareAnalytic($request->all(), $request->input('wage', 0));
        if (isset($arStatistic['error']) && $arStatistic['error']) {
            return redirect()->route('admin.analytic-compare')->withInput($request->all())->withErrors($arStatistic['error']);
        }
        $filter = $analyticSalary->getAnalyticForm($request->all());
        $wageProperties = $analyticSalary->getWagesProperties();

        return view('admin.analytic.compare', [
            'filter' => $filter,
            'wage' => $analyticSalary->wagePropertyId,
            'result' => $arStatistic['result'] ?? '',
            'arStatistic' => $arStatistic['statistic'] ?? [],
            'wageProperties' => $wageProperties
        ]);
    }

}
