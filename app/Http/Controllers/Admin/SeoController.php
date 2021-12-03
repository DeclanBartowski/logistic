<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeoRequest;
use App\Models\Seo;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    private $adminService,
        $prefix = 'seo';

    public function __construct()
    {
        $this->adminService = new AdminService(new Seo(), '', $this->prefix);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->input('getList') == 'Y') {
            return $this->adminService->getList();
        } else {
            return view('admin.templates.index', [
                'columns' => Seo::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'SEO',
                'addText' => 'Добавить seo'
            ]);
        }
    }

    /**
     * @return mixed
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
     * @param SeoRequest $request
     * @return mixed
     */
    public function store(SeoRequest $request)
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Seo $seo
     * @return mixed
     */
    public function edit(Seo $seo)
    {
        $detail = $this->adminService->getDetail($seo->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $seo),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $seo->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param SeoRequest $request
     * @param Seo $seo
     * @return mixed
     */
    public function update(SeoRequest $request, Seo $seo)
    {
        $this->adminService->updateElement($seo->id, $request->validated());
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
