<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithForm;

    public $tax;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'tax.name' => ['required' => 'Tax name is required.'],
            'tax.country' => ['required' => 'Country is required.'],
            'tax.rate' => ['required' => 'Tax rate is required.'],
            'tax.state' => ['nullable'],
            'tax.is_active' => ['nullable'],
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->tax->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tax.form');
    }
}