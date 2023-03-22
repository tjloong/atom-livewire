<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Price;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithForm;

    public $plan;
    public $price;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'price.currency' => ['required' => 'Currency is required.'],
            'price.amount' => ['required' => 'Price is required.'],
            'price.discount' => ['max:100' => 'Discount percentage should be less than 100.'],
            'price.expired_after' => ['required_if' => 'Price valid period is required.'],
            'price.country' => ['nullable'],
            'price.shoutout' => ['nullable'],
            'price.is_recurring' => ['nullable'],
            'price.is_default' => ['nullable'],
            'price.tax_id' => ['nullable'],
            'price.plan_id' => ['required' => 'Unknown plan.'],
        ];
    }

    /**
     * Get readonly property
     */
    public function getReadonlyProperty(): bool
    {
        return $this->price->users()->count() > 0;
    }

    /**
     * Get enabled stripe property
     */
    public function getEnabledStripeProperty(): bool
    {
        return settings('stripe_public_key') && settings('stripe_secret_key');
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

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
    public function render(): mixed
    {
        return atom_view('app.plan.price.form');
    }
}