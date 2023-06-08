<?php

namespace Jiannius\Atom\Http\Livewire\App\Shipping;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithForm;

    public $rate;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'rate.name' => ['required' => 'Rate name is required.'],
            'rate.price' => ['nullable'],
            'rate.condition' => ['nullable'],
            'rate.min' => ['nullable'],
            'rate.max' => ['nullable'],
            'rate.countries' => ['nullable'],
            'rate.is_active' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->rate->countries) $this->rate->countries = [];
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->rate->save();

        $this->emit('submitted', $this->rate->id);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.shipping.form');
    }
}
