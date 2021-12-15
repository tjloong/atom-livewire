<?php

namespace Jiannius\Atom\Components\Builder;

use Illuminate\View\Component;

class Footer extends Component
{
    public $copyright;
    public $phone;
    public $email;
    public $dark;
    public $socials;

    /**
     * Contructor
     * 
     * @return void
     */
    public function __construct(
        $dark = false,
        $copyright = null,
        $phone = null,
        $email = null,
        $facebook = null,
        $instagram = null,
        $twitter = null,
        $linkedin = null,
        $whatsapp = null
    ) {
        $this->copyright = $copyright;
        $this->phone = $phone;
        $this->email = $email;
        $this->dark = $dark;
        $this->socials = collect(compact('facebook', 'twitter', 'instagram', 'linkedin', 'whatsapp'))->filter(fn($val) => $val);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.builder.footer');
    }
}