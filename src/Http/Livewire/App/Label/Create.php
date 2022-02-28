<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Create extends Component
{
    public $type;
    public $label;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->push('Create Label');
        
        $this->label = model('label');
        $this->label->type = $this->type;
    }

    /**
     * Saved
     */
    public function saved()
    {
        session()->flash('flash', 'Label Created::success');
        return redirect()->route('label.listing', [$this->type]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.create');
    }
}