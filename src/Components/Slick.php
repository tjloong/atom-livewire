<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Slick extends Component
{
    public $leftArrow;
    public $rightArrow;

    public function __construct()
    {
        $this->leftArrow = <<<EOL
        <div class="slick-prev absolute z-10 left-2 top-1/2 -translate-y-1/2">
            <button x-ref="prev" type="button" class="w-10 h-10 bg-white/50 text-gray-400 rounded-full flex items-center justify-center">
                <i class="fa fa-chevron-left"></i>
            </button>
        </div>
        EOL;

        $this->rightArrow = <<<EOL
        <div class="slick-next absolute z-10 right-2 top-1/2 -translate-y-1/2">
            <button x-ref="next" type="button" class="w-10 h-10 bg-white/50 text-gray-400 rounded-full flex items-center justify-center">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>    
        EOL;
    }

    public function render() : mixed
    {
        return view('atom::components.slick');
    }
}