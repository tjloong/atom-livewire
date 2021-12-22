<?php

namespace App\Http\Livewire\App\Label;

use App\Models\Label;
use Livewire\Component;

class Update extends Component
{
    public Label $label;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.label.update');
    }

    /**
     * After saved
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Label Updated', 'type' => 'success']);
    }

    /**
     * Delete label
     * 
     * @return void
     */
    public function delete()
    {
        $this->label->delete();

        session()->flash('flash', 'Label Deleted');
        
        return redirect()->route('label.listing', ['tab' => $this->label->type]);
    }
}