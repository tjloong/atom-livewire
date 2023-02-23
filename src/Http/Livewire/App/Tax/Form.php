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
            'tax.name.required' => 'Tax name is required.',
            'tax.country.required' => 'Country is required.',
            'tax.rate.required' => 'Tax rate is required.',
            'tax.rate.number' => 'Invalid tax rate.',
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->tax->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.tax.form');
    }
}