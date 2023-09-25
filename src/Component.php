<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithFileInput;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithSelectInput;
use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    use WithFileInput;
    use WithPopupNotify;
    use WithSelectInput;
    
    public $currentRouteName;
    public $currentUrl;

    // mount
    public function mount()
    {
        $this->currentRouteName = request()->route()->getName();
        $this->currentUrl = url()->current();
    }

    // render
    public function render()
    {
        $class = static::class;
        $view = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');

        if (view()->exists($view)) return view($view);
        if (view()->exists('atom::'.$view)) return view('atom::'.$view);
    }
}