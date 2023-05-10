<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Create extends Component
{
    public $plan;

    protected $listeners = ['submitted'];

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
     * Submitted
     */
    public function submitted($id)
    {
        return redirect()->route('app.plan.update', [$id]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.create');
    }
}