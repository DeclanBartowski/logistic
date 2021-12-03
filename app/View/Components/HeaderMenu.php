<?php

namespace App\View\Components;

use App\Models\MenuType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class HeaderMenu extends Component
{
    public $links;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $url = Request::path();
        if (strpos($url, '/') !== 0) {
            $url = sprintf('/%s', $url);
        }
        $headerMenu = MenuType::where('slug', 'top')->with('items', function ($q) {
            $q->orderBy('sort', 'asc');
        })->first();
        if ($headerMenu->getRelation('items')) {
            foreach ($headerMenu->getRelation('items') as $item) {
                if (strpos($url, $item->link) !== false && url($item->link) != \route('home')) {
                    $item->current = 'Y';
                }
                $this->links[] = $item;
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
        return view('components.header-menu');
    }
}
