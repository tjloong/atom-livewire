<?php

namespace Jiannius\Atom\Http\Livewire\App\Tenant;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithFile;
    use WithPopupNotify;

    public $tenant;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'tenant.name' => ['required' => 'Company name is required.'],
            'tenant.email' => ['required' => 'Contact email is required.'],
            'tenant.phone' => ['required' => 'Contact number is required.'],
            'tenant.country' => ['required' => 'Country is required.'],
            'tenant.website' => 'nullable',
            'tenant.brn' => ['nullable'],
            'tenant.address_1' => ['nullable'],
            'tenant.address_2' => ['nullable'],
            'tenant.city' => ['nullable'],
            'tenant.zip' => ['nullable'],
            'tenant.state' => ['nullable'],
            'tenant.avatar_id' => ['nullable'],
        ];
    }

    /**
     * Updated tenant country
     */
    public function updatedTenantCountry(): void
    {
        $this->tenant->fill(['state' => null]);
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();
        $this->tenant->save();
        $this->emit('submitted', $this->tenant->id);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tenant.form');
    }
}