<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class search_box extends Component
{
    public $queries;

    /**
     * Make sure the queries are available to the view.
     */
    public function __construct($queries)
    {
        $this->queries = $queries;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|\Closure|string
    {
        return view('components.search_box');
    }
}
