<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithDrawer;
use Jiannius\Atom\Traits\Livewire\WithModal;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    use WithDrawer;
    use WithModal;
    use WithPopupNotify;

    // mount
    public function mount()
    {
        //
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