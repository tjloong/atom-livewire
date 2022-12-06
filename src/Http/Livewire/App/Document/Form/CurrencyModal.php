<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Livewire\Component;

class CurrencyModal extends Component
{
    public $inputs;
    public $document;
    public $currencies;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'inputs.currency' => 'required',
            'inputs.currency_rate' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'inputs.currency.required' => __('Currency is required.'),
        ];
    }

    /**
     * Open
     */
    public function open($document, $currencies = [])
    {
        $this->document = $document;
        $this->currencies = $currencies;

        $this->inputs = [
            'currency' => data_get($document, 'currency'),
            'currency_rate' => data_get($document, 'currency_rate'),
        ];

        $this->dispatchBrowserEvent('currency-modal-open');
    }

    /**
     * Get is foreign currency property
     */
    public function getIsForeignCurrencyProperty()
    {
        $isBelongsToAccount = model('document')->enabledBelongsToAccountTrait;
        $defaultCurrency = $isBelongsToAccount ? account_settings('default_currency') : site_settings('default_currency');

        return $defaultCurrency && data_get($this->inputs, 'currency') !== $defaultCurrency;
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->emitUp('setCurrency', $this->inputs);
        $this->dispatchBrowserEvent('currency-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.currency-modal');
    }
}