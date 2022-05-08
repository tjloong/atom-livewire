<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class Submit extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return <<<blade
            <x-button 
                type="submit" 
                icon="check" 
                color="green" 
                :label="\$attributes->get('label') ?? 'Save'"
            />
        blade;
    }
}