<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class Total extends Component
{
    public $items;
    public $inputs;
    public $document;

    protected $listeners = ['setItems'];
    protected $rules = ['document.currency_rate' => 'nullable'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->inputs = [
            'currency' => $this->document->currency,
            'currency_rate' => $this->document->currency_rate,
        ];
    }

    /**
     * Get is foreign currency property
     */
    public function getIsForeignCurrencyProperty()
    {
        return data_get($this->inputs, 'currency') !== $this->document->master_currency;
    }

    /**
     * Get totals property
     */
    public function getTotalsProperty()
    {
        if (!$this->items) return;

        $taxes = collect($this->items)->pluck('taxes')->collapse()->unique('id')
            ->map('collect')
            ->map(fn($tax) => $tax->put(
                'amount', 
                collect($this->items)->pluck('taxes')->collapse()
                    ->where('id', $tax->get('id'))
                    ->sum('amount'),
            ));

        $subtotal = collect($this->items)->sum('subtotal');
        $grandTotal = $subtotal + $taxes->sum('amount');

        return collect([['label' => 'Subtotal', 'amount' => $subtotal]])
            ->concat($taxes)
            ->concat([['label' => 'Grand Total', 'amount' => $grandTotal]]);
    }

    /**
     * Updated inputs
     */
    public function updatedInputs($val, $attr)
    {
        if ($attr === 'currency') {
            $selected = collect($this->document->currency_options)->firstWhere('currency', $val);
            $rate = data_get($selected, 'rate');

            $this->fill(['inputs.currency_rate' => $rate]);
        }

        $this->emitUp('setDocument', $this->inputs);
    }

    /**
     * Set items
     */
    public function setItems($items)
    {
        $this->fill(['items' => $items]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.total');
    }
}