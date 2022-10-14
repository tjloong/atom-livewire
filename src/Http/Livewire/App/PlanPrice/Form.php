<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Form extends Component
{
    public $plan;
    public $planPrice;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'planPrice.country' => 'nullable',
            'planPrice.currency' => 'required',
            'planPrice.amount' => 'required|numeric',
            'planPrice.discount' => 'nullable|numeric|max:100',
            'planPrice.expired_after' => 'required_if:planPrice.is_lifetime,false',
            'planPrice.shoutout' => 'nullable',
            'planPrice.auto_renew' => 'nullable',
            'planPrice.is_lifetime' => 'nullable',
            'planPrice.is_default' => 'nullable',
            'planPrice.plan_id' => 'required',
            'planPrice.tax_id' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'planPrice.currency.required' => 'Currency is required.',
            'planPrice.amount.required' => 'Price is required.',
            'planPrice.amount.numeric' => 'Invalid price.',
            'planPrice.discount.numeric' => 'Invalid discount percentage.',
            'planPrice.discount.max' => 'Invalid discount percentage.',
            'planPrice.expired_after.required_if' => 'Price valid period is required.',
            'planPrice.plan_id.required' => 'Unknown plan.',
        ];
    }
    
    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get readonly property
     */
    public function getReadonlyProperty()
    {
        return $this->planPrice->accounts()->count() > 0;
    }

    /**
     * Get enabled stripe property
     */
    public function getEnabledStripeProperty()
    {
        return site_settings('stripe_public_key') && site_settings('stripe_secret_key');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->planPrice->fill([
            'expired_after' => $this->planPrice->is_lifetime
                ? null
                : $this->planPrice->expired_after,
        ])->save();
        
        if ($this->planPrice->is_default) {
            $this->plan->planPrices()
                ->where('country', $this->planPrice->country)
                ->where('is_default', true)
                ->where('id', '<>', $this->planPrice->id)
                ->update(['is_default' => false]);
        }

        $this->emitUp('saved', $this->planPrice->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan-price.form');
    }
}