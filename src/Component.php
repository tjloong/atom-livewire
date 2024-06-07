<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
{
    use WithPopupNotify;

    public $errors;
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

    // get view
    public function getView() : string
    {
        $class = static::class;

        $path = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');
        
        return view()->exists($path) ? $path : 'atom::'.$path;
    }

    // get view data
    public function getViewData() : array
    {
        return [];
    }

    // get layout
    public function getLayout() : string
    {
        $path = 'layouts.'.request()->portal();

        return view()->exists($path) ? $path : '';
    }

    // render
    public function render()
    {
        // expose error bag so front end can use
        $this->errors = collect($this->getErrorBag()->toArray())->map(fn($e) => head($e))->toArray();

        $layout = $this->getLayout();
        $view = $this->getView();
        $data = $this->getViewData();

        return empty($layout)
            ? view($view, $data)
            : view($view, $data)->layout($layout);
    }
}