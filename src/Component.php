<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithDrawer;
use Jiannius\Atom\Traits\Livewire\WithModal;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
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

        $path = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');
        
        $view = view()->exists($path) ? $path : 'atom::'.$path;

        // get layout
        $route = optional(request()->route())->getName();
        $prefix = $route ? collect(explode('.', $route))->first() : null;
        $path = $prefix ? resource_path('views/layouts/'.$prefix.'.blade.php') : null;
        $layout = $path && file_exists($path) ? 'layouts.'.$prefix : 'layouts.app';

        return view($view)->layout($layout);
    }
}