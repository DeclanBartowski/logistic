<?php

namespace App\View\Components;

use App\Services\Commerce;
use Illuminate\View\Component;

class Social extends Component
{

    public $items;

    /**
     * CommerceBlock constructor.
     */
    public function __construct()
    {
      $this->items = \App\Models\Social::orderBy('sort','asc')->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.soc');
    }
}
