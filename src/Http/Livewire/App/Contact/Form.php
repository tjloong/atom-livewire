<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithFile;
    use WithForm;
    use WithPopupNotify;

    public $contact;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'contact.category' => ['required' => 'Contact category is required.'],
            'contact.type' => ['required' => 'Contact type is required.'],
            'contact.name' => ['required' => 'Name is required.'],
            'contact.owned_by' => ['nullable'],
            'contact.avatar_id' => ['nullable'],
            'contact.email' => ['nullable'],
            'contact.phone' => ['nullable'],
            'contact.fax' => ['nullable'],
            'contact.brn' => ['nullable'],
            'contact.tax_number' => ['nullable'],
            'contact.website' => ['nullable'],
            'contact.address_1' => ['nullable'],
            'contact.address_2' => ['nullable'],
            'contact.city' => ['nullable'],
            'contact.zip' => ['nullable'],
            'contact.country' => ['nullable'],
            'contact.state' => ['nullable'],
        ];
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return $this->contact->exists ? str($this->contact->category)->title()->toString().' Information' : null;
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'types' => [
                ['value' => 'person', 'label' => 'Individual'],
                ['value' => 'company', 'label' => 'Company'],
            ],
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();
        $this->contact->save();

        return $this->submitted();
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->contact->wasRecentlyCreated
            ? redirect()->route('app.contact.view', [$this->contact->id])
            : $this->popup('Contact Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.form');
    }
}