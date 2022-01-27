<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Create extends Component
{
    public $type;
    public $label;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $model = getModelClassName('Label');
        $this->label = new $model([
            'type' => $this->type,
        ]);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.label.create');
    }

    /**
     * After saved
     * 
     * @return void
     */
    public function saved()
    {
        session()->flash('flash', 'Label Created::success');
        return redirect()->route('label.listing', [$this->type]);
    }
}