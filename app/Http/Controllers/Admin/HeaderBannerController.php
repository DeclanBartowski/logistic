<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeaderBannerRequest;
use App\Models\HeaderBanner;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class HeaderBannerController extends Controller
{
    private $adminService,
        $prefix = 'header-banners';

    public function __construct()
    {
        $this->adminService = new AdminService(new HeaderBanner(), '', $this->prefix);
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
                'columns' => HeaderBanner::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Баннер в шапке',
                'addText' => 'Добавить баннер'
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
     * @param HeaderBannerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HeaderBannerRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param HeaderBanner $headerBanner
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(HeaderBanner $headerBanner)
    {
        $detail = $this->adminService->getDetail($headerBanner->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $headerBanner),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $headerBanner->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param HeaderBannerRequest $request
     * @param HeaderBanner $headerBanner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HeaderBannerRequest $request, HeaderBanner $headerBanner): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($headerBanner->id, $request->validated());
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
