<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithModal
{
    public $isModalOpened = false;

    // open modal
    public function openModal($id = null) : void
    {
        $this->isModalOpened = true;
        $this->dispatchBrowserEvent('open-modal', $id ?? $this->id);
    }

    // close modal
    public function closeModal($id = null) : void
    {
        $this->isModalOpened = false;
        $this->dispatchBrowserEvent('close-modal', $id ?? $this->id);
    }
}