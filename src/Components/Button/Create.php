<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Create extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return <<<blade
            <x-button icon="plus" {{ \$attributes }}/>
        blade;
    }
}