<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Livewire\Component;

class Contact extends Component
{
    public $inputs;
    public $document;

    protected $rules = [
        'inputs.contact_id' => 'required',
        'document.type' => 'required',
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->setInputs($this->document->contact);
    }

    /**
     * Get label property
     */
    public function getLabelProperty(): string
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
    public function getTypeProperty(): string
    {
        return in_array($this->document->type, ['purchase-order', 'bill']) ? 'vendor' : 'client';
    }

    /**
     * Updated inputs
     */
    public function updatedInputs($val, $attr): void
    {
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
    public function emitEvent(): void
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
    public function setInputs($contact = null): void
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
    public function getContacts($search = null, $page = 1): array
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
    public function render(): mixed
    {
        return atom_view('app.document.form.contact');
    }
}