<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class File extends Component
{
    public $max;
    public $multiple;

    /**
     * Constructor
     */
    public function __construct(
        $max = null,
        $multiple = false
    ) {
        $this->max = $max ?? config('atom.max_upload_size');
        $this->multiple = $multiple;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.file');
    }
}