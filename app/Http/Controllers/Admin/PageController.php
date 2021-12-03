<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $adminService,
        $prefix = 'pages';

    public function __construct()
    {
        $this->adminService = new AdminService(new Page(), '', $this->prefix);
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
                'columns' => Page::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Текст в сайдбаре',
                'addText' => 'Добавить текст'
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
     * @param PageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Page $page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Page $page)
    {
        $detail = $this->adminService->getDetail($page->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $page),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $page->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PageRequest $request, Page $page): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($page->id, $request->validated());
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
