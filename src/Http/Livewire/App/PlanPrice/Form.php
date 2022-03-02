<?php

namespace Jiannius\Atom\Http\Livewire\App\PlanPrice;

use Livewire\Component;

class Form extends Component
{
    public $plan;
    public $price;

    protected $rules = [
        'price.currency' => 'required',
        'price.amount' => 'required|numeric',
        'price.recurring' => 'required',
        'price.country' => 'nullable',
        'price.is_default' => 'nullable',
        'price.plan_id' => 'required',
    ];

    protected $messages = [
        'price.currency.required' => 'Currency is required.',
        'price.amount.required' => 'Price is required.',
        'price.amount.numeric' => 'Invalid price.',
        'price.recurring.required' => 'Recurring period is required.',
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
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

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