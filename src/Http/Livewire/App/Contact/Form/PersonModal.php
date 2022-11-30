<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact\Form;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class PersonModal extends Component
{
    use WithPopupNotify;
    
    public $input;
    public $contact;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'input.name' => 'required',
            'input.salutation' => 'nullable',
            'input.email' => 'nullable',
            'input.phone' => 'nullable',
            'input.designation' => 'nullable',
            'input.contact_id' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'input.name.required' => __('Person name is required.'),
            'input.contact_id.required' => __('Unknown contact.'),
        ];
    }

    /**
     * Open
     */
    public function open($id = null)
    {
        $this->input = $id
            ? $this->contact->persons()->find($id)
            : model('contact_person')->fill(['contact_id' => $this->contact->id]);

        $this->dispatchBrowserEvent('person-form-modal-open');
    }

    /**
     * Delete
     */
    public function delete()
    {
        if ($id = data_get($this->input, 'id')) {
            $person = $this->contact->persons()->find($id);
            optional($person)->delete();
    
            $this->popup('Contact Person Deleted');
            $this->done();
        }
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->input->save();
        $this->done();
    }

    /**
     * Done
     */
    public function done()
    {
        $this->dispatchBrowserEvent('person-form-modal-close');
        $this->emit('refresh');        
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.form.person-modal');
    }
}