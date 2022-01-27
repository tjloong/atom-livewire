<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Update extends Component
{
    public $label;
    public $component;
    
    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($id)
    {
        $this->label = getModel('Label')->findOrFail($id);
        $this->component = file_exists(resource_path('views/livewire/app/label/' . $this->label->type . '.blade.php'))
            ? 'app.label.' . $this->label->type
            : 'atom.label.form';
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.label.update');
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
        
        return redirect()->route('label.listing', [$this->label->type]);
    }
}