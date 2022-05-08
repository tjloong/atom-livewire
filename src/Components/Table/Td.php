<?php

namespace Jiannius\Atom\Components\Table;

use Illuminate\View\Component;

class Td extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return <<<blade
            <td {{ \$attributes->class(['align-top py-3 px-4 whitespace-nowrap']) }}>
                {{ \$slot }}
            </td>
        blade;
    }
}