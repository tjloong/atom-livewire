<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $plan;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($planId): void
    {
        $this->plan = model('plan')->findOrFail($planId);

        breadcrumbs()->push($this->plan->name);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        if ($this->plan->subscriptions->count()) {
            return $this->popup([
                'title' => 'Unable To Delete Plan',
                'message' => 'There are subscriptions under this plan.',
            ], 'alert', 'error');
        }

        $this->plan->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submitted
     */
    public function submitted()
    {
        return $this->popup('Plan Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.update');
    }
}