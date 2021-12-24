<?php

namespace App\Http\Livewire\App\Label;

use App\Models\Label;
use Livewire\Component;

class Create extends Component
{
    public $label;
    public $type;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->label = new Label([
            'type' => request()->query('type'),
        ]);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.label.create');
    }

    /**
     * After saved
     * 
     * @return void
     */
    public function saved($type)
    {
        session()->flash('flash', 'Label Created::success');
        return redirect()->route('label.listing', ['tab' => $type]);
    }
}