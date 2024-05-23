<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
{
    use WithPopupNotify;

    public $keynonce;

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

    // open/close modal
    public function modal($open = true, $id = null)
    {
        $this->refreshKeynonce();

        $id = $id ?? $this->getName() ?? $this->id;

        if ($open) $this->dispatchBrowserEvent('open-modal', $id);
        else $this->dispatchBrowserEvent('close-modal', $id);
    }

    // get layout
    public function layout() : string
    {
        $path = 'layouts.'.request()->portal();

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