<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Plan extends Component
{
    public $plan;

    /**
     * Mount
     */
    public function mount()
    {
        $this->plan = request()->query('plan')
            ? model('plan')->where('slug', request()->query('plan'))->where('is_active', true)->first()
            : null;

        if ($this->subscriptions->count() && !$this->plan) return redirect()->route('app.billing.view');

        breadcrumbs()->push('Update Plan');        
    }

    /**
     * Get subscriptions property
     */
    public function getSubscriptionsProperty()
    {
        return auth()->user()->account->subscriptions()
            ->status('active')
            ->get();
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        $country = auth()->user()->account->country
            ?? geoip()->getLocation()->iso_code;

        return model('plan')
            ->when($this->subscriptions->count(), function($q) {
                $id = collect([$this->plan->id])
                    ->concat($this->plan->upgradables->pluck('id')->toArray())
                    ->concat($this->plan->downgradables->pluck('id')->toArray())
                    ->unique();

                $q->whereIn('id', $id);
            })
            ->with(['prices' => fn($q) => $q->where('country', $country)])
            ->status('active')
            ->orderBy('id')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.billing.plan');
    }
}