<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
{
    use WithPopupNotify;

    public $isModalOpened = false;
    public $isLayerOpened = false;
    public $isDrawerOpened = false;

    // mount
    public function mount()
    {
        //
    }

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

    // open layer
    public function openLayer($id = null) : void
    {
        $this->isLayerOpened = true;
        $this->dispatchBrowserEvent('open-layer', $id ?? $this->id);
    }

    // close layer
    public function closeLayer($id = null) : void
    {
        $this->isLayerOpened = false;
        $this->dispatchBrowserEvent('close-layer', $id ?? $this->id);
    }

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