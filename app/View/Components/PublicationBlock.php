<?php

namespace App\View\Components;

use App\Services\Blog;
use Illuminate\View\Component;

class PublicationBlock extends Component
{
    public $items;

    /**
     * PublicationBlock constructor.
     * @param Blog $blogService
     */
    public function __construct(Blog $blogService)
    {

        $this->items = $blogService->getRandomItems();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.publication-block');
    }
}
