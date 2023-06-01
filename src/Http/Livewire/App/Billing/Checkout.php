<?php

namespace Jiannius\Atom\Http\Livewire\App\Billing;

use Livewire\Component;

class Checkout extends Component
{
    public $preselect;

    /**
     * Mount
     */
    public function mount()
    {
        $this->preselect = request()->query('plan');

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return user()->can('plan') ? 'Change Subscription' : 'Subscribe Plan';
    }

    /**
     * Get plans property
     */
    public function getPlansProperty()
    {
        return model('plan')
            ->subscribeable()
            ->with('prices')
            ->get()
            ->map(function ($plan) {
                $plan->subscription = model('plan_subscription')
                    ->status(['active', 'future'])
                    ->plan($plan->id)
                    ->where('user_id', user('id'))
                    ->latest('id')
                    ->first();
                return $plan;
            });
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.billing.checkout');
    }
}