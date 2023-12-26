<?php

namespace App\View\Components;

use Illuminate\View\Component;

class producer extends Component
{
    public $producer;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($producer)
    {
        $this->producer = $producer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.producer');
    }
}
