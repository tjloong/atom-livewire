<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Create extends Component
{
    public $plan;

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->plan = model('plan')->fill([
            'is_active' => true,
        ]);

        breadcrumbs()->push('Create Plan');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.create');
    }
}