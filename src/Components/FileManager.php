<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class FileManager extends Component
{
    public $canEditVisibility = true;

    public function render() : mixed
    {
        return view('atom::components.file-manager');
    }
}