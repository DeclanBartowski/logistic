<?php

namespace App\View\Components;


use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use App\Models\Page;

class SideText extends Component
{
    public $page;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $path = Request::path();
        if (strpos($path, '/') !== 0) {
            $path = sprintf('/%s', $path);
        }
        $this->page = Page::where('link', $path)->first();
        if (!$this->page) {
            $this->page = Page::where('link',
                Route::current()->getCompiled()->getStaticPrefix())->first();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.side-text');
    }
}
