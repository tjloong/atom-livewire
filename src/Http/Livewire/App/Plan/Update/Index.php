<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'prices';
    public $plan;

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

        return redirect()->route('app.settings', ['plans'])->with('info', 'Plan Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.update.index');
    }
}