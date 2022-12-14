<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Create extends Component
{
    public $plan;

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->push('Create Plan');

        $this->plan = model('plan')->fill([
            'is_active' => true,
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.create');
    }
}