<?php

namespace App\View\Components;

use App\Models\MenuGroup;
use App\Models\MenuType;
use Illuminate\View\Component;

class FooterMenu extends Component
{
    public $links;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $footerMenu = MenuType::where('slug', 'bottom')->with('items', function ($q) {
            $q->orderBy('sort', 'asc');
        })->first();
        if ($footerMenu->getRelation('items')) {
            $arGroups = $footerMenu->getRelation('items')->groupBy('menu_group_id')->toArray();
            $arGroupNames = MenuGroup::whereIn('id', array_keys($arGroups))->get()->keyBy('id')->toArray();
            if ($arGroupNames) {
                foreach ($arGroups as $key => $arGroup) {
                    $group = [
                        'name' => $arGroupNames[$key]['name'],
                        'items' => $arGroup
                    ];
                    $this->links[] = $group;
                }
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footer-menu');
    }
}
