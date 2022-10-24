<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Livewire\Component;

class TaxFormModal extends Component
{
    public $tax;

    protected $listeners = ['open'];

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
     * Open
     */
    public function open($id = null)
    {
        $this->tax = $id
            ? model('tax')
                ->when(model('tax')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
                ->findOrFail($id)
            : model('tax')->fill([
                'country' => optional(auth()->user()->account)->country,
                'is_active' => true,
            ]);
        
        $this->dispatchBrowserEvent('tax-form-modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->tax->save();

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('tax-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences.tax-form-modal');
    }
}