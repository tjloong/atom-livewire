<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Update extends Component
{
    public $label;
    
    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->label = get_model('Label')->findOrFail($id);

        breadcrumb($this->label->name);
    }

    /**
     * Get component name property
     */
    public function getComponentNameProperty()
    {
        return file_exists(resource_path('views/livewire/app/label/' . $this->label->type . '.blade.php'))
            ? 'app.label.' . $this->label->type
            : 'atom.label.form';
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Label Updated', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->label->delete();

        session()->flash('flash', 'Label Deleted');
        
        return redirect()->route('label.listing', [$this->label->type]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update');
    }
}