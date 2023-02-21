<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithPopupNotify;
    
    public $contact;
    public $person;

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'person.name' => 'required',
            'person.salutation' => 'nullable',
            'person.email' => 'nullable',
            'person.phone' => 'nullable',
            'person.designation' => 'nullable',
            'person.contact_id' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'person.name.required' => 'Person name is required.',
            'person.contact_id.required' => 'Unknown contact.',
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->person->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.person.form');
    }
}