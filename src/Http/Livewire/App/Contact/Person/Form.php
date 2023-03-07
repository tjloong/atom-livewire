<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Person;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;
    
    public $person;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'person.name' => ['required' => 'Person name is require.'],
            'person.salutation' => ['nullable'],
            'person.email' => ['nullable'],
            'person.phone' => ['nullable'],
            'person.designation' => ['nullable'],
            'person.contact_id' => ['required' => 'Unknown contact.'],
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->person->save();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.contact.person.form');
    }
}