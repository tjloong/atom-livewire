<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'prices';
    public $plan;

    protected $listeners = ['saved'];
    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($plan)
    {
        $this->plan = model('plan')->findOrFail($plan);
        breadcrumbs()->push($this->plan->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->plan->delete();

        session()->flash('flash', 'Plan Deleted');
        
        return redirect()->route('app.plan.listing');
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
        return view('atom::app.plan.update.index');
    }
}