<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class PriceModal extends Component
{
    use WithForm;

    public $price;

    protected $listeners = ['open'];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'price.code' => [
                'required' => 'Price code is required.',
                function ($attr, $value, $fail) {
                    if (model('plan_price')->where('code', $value)->where('id', '<>', $this->price->id)->count()) {
                        $fail('Price code is taken.');
                    }
                },
            ],
            'price.description' => ['required' => 'Description is required.'],
            'price.amount' => ['nullable'],
            'price.valid' => ['nullable'],
            'price.valid.count' => ['nullable'],
            'price.valid.interval' => ['nullable'],
            'price.is_recurring' => ['nullable'],
            'price.is_active' => ['nullable'],
            'price.plan_id' => ['required' => 'Plan is required.'],
        ];
    }

    /**
     * Open
     */
    public function open($data): void
    {
        $this->price = ($id = data_get($data, 'id'))
            ? model('plan_price')->find($id) 
            : model('plan_price')->fill($data);

        if (!$this->price->valid) {
            $this->price->fill([
                'valid' => ['count' => null, 'interval' => null],
            ]);
        }

        $this->dispatchBrowserEvent('price-modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->validateForm();

        $this->price->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('price-modal-close');
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->price->delete();
        $this->emit('refresh');
        $this->dispatchBrowserEvent('price-modal-close');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.price-modal');
    }
}