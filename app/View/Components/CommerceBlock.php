<?php

namespace App\View\Components;

use App\Services\Commerce;
use Illuminate\View\Component;

class CommerceBlock extends Component
{
    public $type = '',
        $items;

    /**
     * CommerceBlock constructor.
     * @param string $type
     */
    public function __construct($type = '')
    {
        $this->type = $type;
        $commerceService = Commerce::getInstance();
        $this->items = $commerceService->getItems();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.commerce-block');
    }
}
