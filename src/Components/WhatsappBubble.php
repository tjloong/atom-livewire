<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class WhatsappBubble extends Component
{
    public function render()
    {
        return view('atom::components.whatsapp-bubble');
    }
}