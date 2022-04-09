<?php

namespace Jiannius\Atom\Components\Input;

use Illuminate\View\Component;

class File extends Component
{
    public $max;
    public $multiple;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct(
        $max = null,
        $multiple = false
    ) {
        $this->max = $max ?? config('atom.max_upload_size');
        $this->multiple = $multiple;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::components.input.file');
    }
}