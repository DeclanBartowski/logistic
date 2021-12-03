<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WagePropertyRequest;
use App\Models\WageProperty;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class WagePropertyController extends Controller
{
    private $adminService,
        $prefix = 'wage-properties';

    public function __construct()
    {
        $this->adminService = new AdminService(new WageProperty(), '', $this->prefix,
            [
                [
                    'name' => 'Варианты списка',
                    'relation' => 'options',
                    'type' => 'elementList',
                    'sort' => ['sort' => 'asc'],
                    'conditions' => [
                        'type' => ['list']
                    ],
                    'model' => 'App\Models\WagePropertyOption'
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
                'columns' => WageProperty::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Свйоства зарплат',
                'addText' => 'Добавить свойство'
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
     * @param WagePropertyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WagePropertyRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param WageProperty $wageProperty
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(WageProperty $wageProperty)
    {
        $detail = $this->adminService->getDetail($wageProperty->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $wageProperty),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $wageProperty->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param WagePropertyRequest $request
     * @param WageProperty $wageProperty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(WagePropertyRequest $request, WageProperty $wageProperty): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($wageProperty->id, $request->validated());
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
