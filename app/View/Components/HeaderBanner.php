<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class HeaderBanner extends Component
{
    public $banner;

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
        $this->banner = \App\Models\HeaderBanner::where('link', $path)->first();
        if (!$this->banner) {
            $this->banner = \App\Models\HeaderBanner::where('link',
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
        return view('components.header-banner');
    }
}
