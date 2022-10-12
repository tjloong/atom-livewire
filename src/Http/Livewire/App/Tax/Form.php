<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;

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
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->tax->save();

        if ($this->tax->wasRecentlyCreated) return redirect()->route('app.settings', ['taxes']);
        else $this->popup('Tax Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.tax.form');
    }
}