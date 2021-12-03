<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuTypeRequest;
use App\Models\MenuType;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class MenuTypeController extends Controller
{
    private $adminService,
        $prefix = 'menu-types';

    public function __construct()
    {
        $this->adminService = new AdminService(new MenuType(), '', $this->prefix);
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
                'columns' => MenuType::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Типы меню',
                'addText' => 'Добавить тип'
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
     * @param MenuTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param MenuType $menuType
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(MenuType $menuType)
    {
        $detail = $this->adminService->getDetail($menuType->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $menuType),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $menuType->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param MenuTypeRequest $request
     * @param MenuType $menuType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuTypeRequest $request, MenuType $menuType): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($menuType->id, $request->validated());
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
