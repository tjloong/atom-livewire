<?php

namespace Jiannius\Atom\Http\Livewire\App\Shipping;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithForm;

    public $rate;
    public $inputs;

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
            'rate.is_active' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->fill([
            'inputs.countries' => $this->rate->countries->pluck('name')->toArray(),
        ]);
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->rate->save();

        // countries
        $this->rate->countries()->whereNotIn('name', data_get($this->inputs, 'countries'))->delete();

        foreach (data_get($this->inputs, 'countries') as $country) {
            if (!$this->rate->countries()->where('name', $country)->count()) {
                $this->rate->countries()->create(['name' => $country]);
            }
        }

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
