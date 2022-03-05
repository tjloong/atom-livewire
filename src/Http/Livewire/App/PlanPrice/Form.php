<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Form extends Component
{
    public $plan;
    public $price;

    protected $rules = [
        'price.country' => 'nullable',
        'price.currency' => 'required',
        'price.amount' => 'required|numeric',
        'price.expired_after' => 'required_if:price.is_lifetime,false',
        'price.shoutout' => 'nullable',
        'price.is_lifetime' => 'nullable',
        'price.is_default' => 'nullable',
        'price.plan_id' => 'required',
    ];

    protected $messages = [
        'price.currency.required' => 'Currency is required.',
        'price.amount.required' => 'Price is required.',
        'price.amount.numeric' => 'Invalid price.',
        'price.expired_after.required_if' => 'Price valid period is required.',
        'price.plan_id.required' => 'Unknown plan.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get disabled property
     */
    public function getDisabledProperty()
    {
        if (enabled_module('tenants')) return $this->price->tenants()->count() > 0;
        if (enabled_module('signups')) return $this->price->signups()->count() > 0;

        return false;
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->price->expired_after = $this->price->is_lifetime ? null : $this->price->expired_after;
        $this->price->save();
        
        if ($this->price->is_default) {
            $this->plan->prices()
                ->where('is_default', true)
                ->where('id', '<>', $this->price->id)
                ->update(['is_default' => false]);
        }

        $this->emitUp('saved', $this->price->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan-price.form', [
            'disabled' => $this->disabled,
        ]);
    }
}