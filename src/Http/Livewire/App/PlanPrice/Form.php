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
        'price.discount' => 'nullable|numeric|max:100',
        'price.expired_after' => 'required_if:price.is_lifetime,false',
        'price.shoutout' => 'nullable',
        'price.is_lifetime' => 'nullable',
        'price.is_default' => 'nullable',
        'price.plan_id' => 'required',
        'price.tax_id' => 'nullable',
    ];

    protected $messages = [
        'price.currency.required' => 'Currency is required.',
        'price.amount.required' => 'Price is required.',
        'price.amount.numeric' => 'Invalid price.',
        'price.discount.numeric' => 'Invalid discount percentage.',
        'price.discount.max' => 'Invalid discount percentage.',
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
     * Get readonly property
     */
    public function getReadonlyProperty()
    {
        return $this->price->accounts()->count() > 0;
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
        return view('atom::app.plan-price.form');
    }
}