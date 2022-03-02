<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Update extends Component
{
    public $plan;
    public $search;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->plan = model('plan')->findOrFail($id);
        breadcrumbs()->push($this->plan->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->plan->delete();

        session()->flash('flash', 'Plan Deleted');
        
        return redirect()->route('plan.listing');
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Plan Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.update');
    }
}