<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Livewire\Component;

class Form extends Component
{
    public $plan;
    public $price;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'price.country' => 'nullable',
            'price.currency' => 'required',
            'price.amount' => 'required|numeric',
            'price.discount' => 'nullable|numeric|max:100',
            'price.expired_after' => 'required_if:price.is_lifetime,false',
            'price.shoutout' => 'nullable',
            'price.is_recurring' => 'nullable',
            'price.is_default' => 'nullable',
            'price.plan_id' => 'required',
            'price.tax_id' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'price.currency.required' => 'Currency is required.',
            'price.amount.required' => 'Price is required.',
            'price.amount.numeric' => 'Invalid price.',
            'price.discount.numeric' => 'Invalid discount percentage.',
            'price.discount.max' => 'Invalid discount percentage.',
            'price.expired_after.required_if' => 'Price valid period is required.',
            'price.plan_id.required' => 'Unknown plan.',
        ];
    }

    /**
     * Get readonly property
     */
    public function getReadonlyProperty()
    {
        return $this->price->users()->count() > 0;
    }

    /**
     * Get enabled stripe property
     */
    public function getEnabledStripeProperty()
    {
        return settings('stripe_public_key') && settings('stripe_secret_key');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->price->fill([
            'amount' => is_numeric($this->price->amount) ? $this->price->amount : null,
            'discount' => is_numeric($this->price->discount) ? $this->price->discount : null,
            'expired_after' => is_numeric($this->price->expired_after) ? $this->price->expired_after : null,
        ])->save();
        
        if ($this->price->is_default) {
            $this->plan->prices()
                ->where('country', $this->price->country)
                ->where('is_default', true)
                ->where('id', '<>', $this->price->id)
                ->update(['is_default' => false]);
        }

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.price.form');
    }
}