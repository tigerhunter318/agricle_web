<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Recruitment extends Component
{
    public $recruitment;
    public $count;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($recruitment, $count)
    {
        $this->recruitment = $recruitment;
        $this->count = $count;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.recruitment');
    }
}
