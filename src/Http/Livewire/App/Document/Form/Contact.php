<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Livewire\Component;

class Contact extends Component
{
    public $inputs;
    public $document;

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'inputs.contact_id' => 'required',
            'document.type' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'inputs.contact_id.required' => __('Please select a contact.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->setInputs($this->document->contact);
    }

    /**
     * Get label property
     */
    public function getLabelProperty()
    {
        return [
            'quotation' => 'Client',
            'sales-order' => 'Client',
            'invoice' => 'Bill To',
            'delivery-order' => 'Deliver To',
            'purchase-order' => 'Vendor',
            'bill' => 'Billed By',
        ][$this->document->type];
    }

    /**
     * Get type property
     */
    public function getTypeProperty()
    {
        return in_array($this->document->type, ['purchase-order', 'bill']) ? 'vendor' : 'client';
    }

    /**
     * Updated inputs
     */
    public function updatedInputs($val, $attr)
    {
        $this->resetValidation();

        if ($attr === 'contact_id') {
            $this->setInputs(model('contact')->find($val));
            $this->validate();
            $this->emitEvent();
        }
        else $this->emitEvent();
    }

    /**
     * Emit event
     */
    public function emitEvent()
    {
        $this->emitUp('setDocument', Arr::only($this->inputs, [
            'name', 
            'address', 
            'person', 
            'payterm', 
            'contact_id',
        ]));
    }

    /**
     * Set inputs
     */
    public function setInputs($contact = null)
    {
        $this->inputs = array_merge([
            'name' => null,
            'address' => null,
            'person' => null,
            'payterm' => null,
            'contact_id' => null,
            'metadata' => null,
        ], $contact ? [
            'name' => $contact->name,
            'address' => format_address($contact->addresses ? $contact->addresses->first() : $contact),
            'person' => $contact->persons->count() ? $contact->persons->first()->name : null,
            'payterm' => $this->document->type === 'delivery-order' ? null : $contact->payterm,
            'contact_id' => $contact->id,
            'metadata' => [
                'avatar' => optional($contact->logo ?? $contact->avatar)->url,
                'addresses' => optional($contact->addresses)->toArray(),
                'persons' => $contact->persons->pluck('name')->unique()->toArray(),
            ],
        ] : []);
    }

    /**
     * Get contacts
     */
    public function getContacts($search = null, $page = 1)
    {
        return model('contact')
            ->readable()
            ->filter([
                'type' => $this->type,
                'search' => $search,
            ])
            ->orderBy('name')
            ->toPage($page)
            ->through(fn($contact) => [
                'avatar' => optional($contact->logo)->url,
                'value' => $contact->id,
                'label' => $contact->name,
                'small' => $contact->email,
            ])
            ->toArray();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.contact');
    }
}