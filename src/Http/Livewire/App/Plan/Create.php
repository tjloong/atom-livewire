<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Create extends Component
{
    public $plan;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->push('Create Plan');

        $this->plan = model('plan');
        $this->plan->is_active = true;
    }

    /**
     * Saved
     */
    public function saved($id)
    {
        return redirect()->route('app.plan.update', [$id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.create');
    }
}