<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Tr extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return <<<blade
            <tr class="border-b last:border-0 hover:bg-slate-100">
                {{ \$slot }}
            </tr>
        blade;
    }
}