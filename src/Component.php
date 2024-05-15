<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
{
    use WithPopupNotify;

    public $keynonce;
    public $isModalOpened = false;
    public $isLayerOpened = false;
    public $isDrawerOpened = false;

    // mount
    public function mount()
    {
        //
    }

    public function wirekey($prefix = null) : string
    {
        if ($this->keynonce) return $prefix.'-'.$this->keynonce;
        else {
            $this->refreshKeynonce();
            return $this->wirekey($prefix);
        }
    }

    // refresh key nonce
    public function refreshKeynonce() : void
    {
        $this->keynonce = uniqid();
    }

    // open modal
    public function openModal($id = null) : void
    {
        $this->isModalOpened = true;
        $this->refreshKeynonce();
        $this->dispatchBrowserEvent('open-modal', $id ?? $this->getName() ?? $this->id);
    }

    // close modal
    public function closeModal($id = null) : void
    {
        $this->isModalOpened = false;
        $this->dispatchBrowserEvent('close-modal', $id ?? $this->getName() ?? $this->id);
    }

    // open layer
    public function openLayer($id = null) : void
    {
        $this->isLayerOpened = true;
        $this->refreshKeynonce();
        $this->dispatchBrowserEvent('open-layer', $id ?? $this->getName() ?? $this->id);
    }

    // close layer
    public function closeLayer($id = null) : void
    {
        $this->isLayerOpened = false;
        $this->dispatchBrowserEvent('close-layer', $id ?? $this->getName() ?? $this->id);
    }

    // open drawer
    public function openDrawer($id = null) : void
    {
        $this->isDrawerOpened = true;
        $this->refreshKeynonce();
        $this->dispatchBrowserEvent('open-drawer', $id ?? $this->getName() ?? $this->id);
    }

    // close drawer
    public function closeDrawer($id = null) : void
    {
        $this->isDrawerOpened = false;
        $this->dispatchBrowserEvent('close-drawer', $id ?? $this->getName() ?? $this->id);
    }

    // get layout
    public function layout() : string
    {
        $route = optional(request()->route())->getName();
        $prefix = $route ? collect(explode('.', $route))->first() : 'app';
        $path = 'layouts.'.$prefix;

        return view()->exists($path) ? $path : '';
    }

    // render
    public function render()
    {
        $class = static::class;

        $path = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');
        
        $view = view()->exists($path) ? $path : 'atom::'.$path;

        $layout = $this->layout();

        return empty($layout) ? view($view) : view($view)->layout($layout);
    }
}