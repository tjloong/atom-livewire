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
    public function mount($label)
    {
        $this->label = model('label')
            ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->findOrFail($label);

        breadcrumbs()->push($this->label->name);
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
        
        return redirect()->route('app.label.listing', [$this->label->type]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update');
    }
}