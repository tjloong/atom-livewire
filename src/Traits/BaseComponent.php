<?php

namespace Jiannius\Atom\Traits;

trait BaseComponent
{
    public $isFullpage;

    /**
     * Mount
     */
    public function mountBaseComponent()
    {
        $this->isFullpage = is_livewire_fullpage($this);
    }
}