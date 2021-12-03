<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingsRequest;
use App\Models\SiteSettings;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    private $adminService,
        $prefix = 'site-settings';

    public function __construct()
    {
        $this->adminService = new AdminService(new SiteSettings(), '', $this->prefix);
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
                'columns' => SiteSettings::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Настройки сайта',
                'addText' => 'Добавить настройки'
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
     * @param SiteSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SiteSettingsRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param SiteSettings $siteSetting
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(SiteSettings $siteSetting)
    {
        $detail = $this->adminService->getDetail($siteSetting->id);

        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $siteSetting),
        ]);
    }

    /**
     * @param SiteSettingsRequest $request
     * @param SiteSettings $siteSetting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SiteSettingsRequest $request, SiteSettings $siteSetting)
    {
        $this->adminService->updateElement($siteSetting->id, $request->validated());
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
