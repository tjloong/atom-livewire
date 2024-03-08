<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithDrawer
{
    public $isDrawerOpened = false;

    // open drawer
    public function openDrawer($id = null) : void
    {
        $this->isDrawerOpened = true;
        $this->dispatchBrowserEvent('open-drawer', $id ?? $this->id);
    }

    // close drawer
    public function closeDrawer($id = null) : void
    {
        $this->isDrawerOpened = false;
        $this->dispatchBrowserEvent('close-drawer', $id ?? $this->id);
    }
}