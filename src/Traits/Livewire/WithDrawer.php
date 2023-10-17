<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithDrawer
{
    public $drawers = [];

    // open drawer
    public function openDrawer($id) : void
    {
        $this->drawers[$id] = true;
        $this->dispatchBrowserEvent($id.'-open');
    }

    // close drawer
    public function closeDrawer($id) : void
    {
        $this->drawers[$id] = false;
        $this->dispatchBrowserEvent($id.'-close');
    }

    // check is drawer open
    public function isDrawerOpened($id) : bool
    {
        return data_get($this->drawers, $id, false);
    }
}