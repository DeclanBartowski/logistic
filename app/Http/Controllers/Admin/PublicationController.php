<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublicationRequest;
use App\Models\Publication;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    private $adminService,
        $prefix = 'publications',
        $route = 'blog.show';

    public function __construct()
    {
        $this->adminService = new AdminService(new Publication(), '', $this->prefix, [
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
                'columns' => Publication::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Публикации',
                'addText' => 'Добавить публикацию'
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
     * @param PublicationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PublicationRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated(),
            $this->route,
            $request->only(['seo_title', 'seo_description', 'seo_keywords'])
        );
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Publication $publication
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Publication $publication)
    {
        $detail = $this->adminService->getDetail($publication->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $publication),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $publication->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param PublicationRequest $request
     * @param Publication $publication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PublicationRequest $request, Publication $publication): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($publication->id, $request->validated(),
            $this->route,
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
