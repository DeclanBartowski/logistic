<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VacancyRequest;
use App\Models\Vacancy;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    private $adminService,
        $prefix = 'vacancies',
        $route = 'vacancies.show';

    public function __construct()
    {
        $this->adminService = new AdminService(new Vacancy(), '', $this->prefix, [
            [
                'name' => 'SEO',
                'type' => 'seo',
                'route' => $this->route
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->input('getList') == 'Y') {
            return $this->adminService->getList();
        } else {
            return view('admin.templates.index', [
                'columns' => Vacancy::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Вакансии',
                'addText' => 'Добавить вакансию'
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $arTabs = $this->adminService->getCreate(true, $this->route);
        return view('admin.templates.detail', [
            'tabs' => $arTabs,
            'action' => route(sprintf('admin.%s.store', $this->prefix))
        ]);
    }

    /**
     * @param VacancyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VacancyRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated(), $this->route,
            $request->only(['seo_title', 'seo_description', 'seo_keywords'])
        );
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Vacancy $vacancy
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Vacancy $vacancy)
    {
        $detail = $this->adminService->getDetail($vacancy->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $vacancy),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $vacancy->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param VacancyRequest $request
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VacancyRequest $request, Vacancy $vacancy): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($vacancy->id, $request->validated(), $this->route,
            $request->only(['seo_title', 'seo_description', 'seo_keywords'])
        );
        return back()->with('status', 'Элемент изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $this->adminService->delete($id, $this->route);
    }
}
