<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuGroupRequest;
use App\Models\MenuGroup;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class MenuGroupController extends Controller
{
    private $adminService,
        $prefix = 'menu-groups';

    public function __construct()
    {
        $this->adminService = new AdminService(new MenuGroup(), '', $this->prefix);
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
                'columns' => MenuGroup::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Группы меню',
                'addText' => 'Добавить группу'
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
     * @param MenuGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuGroupRequest $request)
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param MenuGroup $menuGroup
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(MenuGroup $menuGroup)
    {
        $detail = $this->adminService->getDetail($menuGroup->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $menuGroup),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $menuGroup->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param MenuGroupRequest $request
     * @param MenuGroup $menuGroup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuGroupRequest $request, MenuGroup $menuGroup): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($menuGroup->id, $request->validated());
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
