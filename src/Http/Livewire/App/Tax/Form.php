<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Form extends Component
{
    public $tax;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'tax.name' => 'required',
            'tax.country' => 'required',
            'tax.region' => 'nullable',
            'tax.rate' => 'required|numeric',
            'tax.is_active' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages() {
        return [
            'tax.name.required' => __('Tax name is required.'),
            'tax.country.required' => __('Country is required.'),
            'tax.rate.required' => __('Tax rate is required.'),
            'tax.rate.number' => __('Invalid tax rate.'),
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
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->tax->save();

        $this->emitUp('saved', $this->tax->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.tax.form');
    }
}