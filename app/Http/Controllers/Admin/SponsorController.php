<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SponsorRequest;
use App\Models\Sponsor;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    private $adminService,
        $prefix = 'sponsors';

    public function __construct()
    {
        $this->adminService = new AdminService(new Sponsor(), '', $this->prefix);
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
                'columns' => Sponsor::$listFields,
                'addLink' => route(sprintf('admin.%s.create', $this->prefix)),
                'title' => 'Спонсоры',
                'addText' => 'Добавить спонсора'
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
     * @param SponsorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SponsorRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->addElement($request->validated());
        return redirect()->route(sprintf('admin.%s.index', $this->prefix))->with('status', 'Элемент добавлен');
    }


    /**
     * @param Sponsor $sponsor
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Sponsor $sponsor)
    {
        $detail = $this->adminService->getDetail($sponsor->id);
        return view('admin.templates.detail', [
            'method' => 'PATCH',
            'item' => $detail['item'],
            'tabs' => $detail['tabs'],
            'action' => route(sprintf('admin.%s.update', $this->prefix), $sponsor),
            'delete' => route(sprintf('admin.%s.destroy', $this->prefix), $sponsor->id),
            'back' => route(sprintf('admin.%s.index', $this->prefix))
        ]);
    }

    /**
     * @param SponsorRequest $request
     * @param Sponsor $sponsor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SponsorRequest $request, Sponsor $sponsor): \Illuminate\Http\RedirectResponse
    {
        $this->adminService->updateElement($sponsor->id, $request->validated());
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
