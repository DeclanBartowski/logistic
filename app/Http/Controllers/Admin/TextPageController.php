<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TextPageRequest;
use App\Models\TextPage;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class TextPageController extends Controller
{
    private $adminService,
        $prefix = 'text-pages',
        $route = 'text-page';

    public function __construct()
    {
        $this->adminService = new AdminService(new TextPage(), '', $this->prefix, [
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
                'columns' => TextPage::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Текстовые страницы',
                'addText' => 'Добавить страницу'
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
     * @param TextPageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TextPageRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated(), $this->route,
            $request->only(['seo_title', 'seo_description', 'seo_keywords'])
        );
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param TextPage $textPage
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(TextPage $textPage)
    {
        $detail = $this->adminService->getDetail($textPage->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $textPage),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $textPage->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param TextPageRequest $request
     * @param TextPage $textPage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TextPageRequest $request, TextPage $textPage): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($textPage->id, $request->validated(), $this->route,
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
