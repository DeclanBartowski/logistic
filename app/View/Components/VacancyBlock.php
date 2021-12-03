<?php

namespace App\View\Components;


use App\Services\Vacancy;
use Illuminate\View\Component;

class VacancyBlock extends Component
{
    public $items;

    /**
     * PublicationBlock constructor.
     * @param Vacancy $vacancyService
     */
    public function __construct(Vacancy $vacancyService)
    {

        $this->items = $vacancyService->getRandomItems();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.vacancy-block');
    }
}
