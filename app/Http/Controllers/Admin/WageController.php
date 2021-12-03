<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WageRequest;
use App\Models\Wage;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class WageController extends Controller
{
    private $adminService,
        $prefix = 'wages';

    public function __construct()
    {
        $this->adminService = new AdminService(new Wage(), '', $this->prefix,
            [
                [
                    'name' => 'Свойства',
                    'relation' => 'properties',
                    'type' => 'propertyList',
                    'model' => 'App\Models\WagePropertyValue',
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
                'columns' => Wage::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Зарплаты',
                'addText' => 'Добавить зарплату'
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $arTabs = $this->adminService->getCreate();
        return view('admin.templates.detail', [
            'tabs' => $arTabs,
            'action' => route(sprintf('admin.%s.store', $this->prefix))
        ]);
    }

    /**
     * @param WageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WageRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Wage $wage
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Wage $wage)
    {
        $detail = $this->adminService->getDetail($wage->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'send_mail' => true,
            'action' => route(sprintf('admin.%s.update', $this->prefix), $wage),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $wage->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param WageRequest $request
     * @param Wage $wage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(WageRequest $request, Wage $wage): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($wage->id, $request->validated());
        return back()->with('status', 'Элемент изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $this->adminService->delete($id);
    }
}
