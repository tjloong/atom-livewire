<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;

class Update extends Component
{
    public $tab;
    public $plan;

    /**
     * Mount
     */
    public function mount($planId)
    {
        $this->plan = model('plan')->findOrFail($planId);
        $this->tab = $this->tab ?? data_get($this->tabs, '0.slug');

        breadcrumbs()->push($this->plan->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['slug' => 'info', 'label' => 'Plan Information', 'livewire' => 'app.plan.form'],
            ['slug' => 'price', 'label' => 'Plan Prices', 'livewire' => 'app.plan.price.listing', 'count' => $this->plan->prices->count()],
        ];
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->plan->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.update');
    }
}