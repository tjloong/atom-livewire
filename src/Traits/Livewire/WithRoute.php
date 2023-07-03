<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithRoute
{
    public $currentRouteName;
    public $currentUrl;

    // mount
    public function mountWithRoute()
    {
        $this->currentRouteName = request()->route()->getName();
        $this->currentUrl = url()->current();
    }
}