<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithDrawer
{
    public $isDrawerOpened = false;

    // open drawer
    public function openDrawer() : void
    {
        $this->isDrawerOpened = true;
        $this->dispatchBrowserEvent('open-drawer', $this->id);
    }

    // close drawer
    public function closeDrawer() : void
    {
        $this->isDrawerOpened = false;
        $this->dispatchBrowserEvent('close-drawer', $this->id);
    }
}