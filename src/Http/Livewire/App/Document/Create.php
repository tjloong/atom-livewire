<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $type;
    public $document;

    /**
     * Mount
     */
    public function mount(): void
    {
        if (!in_array($this->type, model('document')->types)) abort(404);

        $this->authorize($this->type.'.create');

        $this->setDocument();
        $this->setConvertFrom();
        $this->setContact();
        $this->setExtraData();

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return str()->headline('Create '.$this->type);
    }

    /**
     * Set document
     */
    public function setDocument(): void
    {
        $this->document = model('document')->fill([
            'type' => $this->type,
            'owned_by' => user('id'),
        ]);

        $this->document->setPrefixAndPostfix();
    }

    /**
     * Set convert from
     */
    public function setConvertFrom()
    {
        if ($srcId = request()->query('convert_from') ?? request()->query('convertFrom')) {
            if ($src = model('document')->readable()->find($srcId)) {
                if ($this->document->type === 'invoice' && !in_array($src->type, ['quotation', 'sales-order'])) return;
                if ($this->document->type === 'bill' && $src->type !== 'purchase-order') return;
                if ($this->document->type === 'delivery-order' && $src->type !== 'invoice') return;

                $this->document->fill(['converted_from_id' => $src->id]);
            }
        }
    }

    /**
     * Set contact
     */
    public function setContact(): void
    {
        if (
            $contact = optional($this->document->convertFrom)->contact
                ?? model('contact')->readable()->find(
                    request()->query('contact_id') ?? request()->query('contactId')
                )
        ) {
            $this->document->fill([
                'name' => $contact->name,
                'address' => $contact->addresses
                    ? format_address(optional($contact->addresses->first()))
                    : format_address($contact),
                'person' => optional($contact->persons->first())->name,
                'payterm' => data_get($contact->data, 'payterm'),
                'contact_id' => $contact->id,
            ]);
        }
    }

    /**
     * Set extra data
     */
    public function setExtraData(): void
    {
        if ($this->type === 'quotation') $this->document->fill(['data' => ['valid_for' => 14]]);
        elseif ($this->type === 'delivery-order') $this->document->fill(['data' => ['delivery_channel' => null]]);
        elseif ($this->type === 'purchase-order') $this->document->fill(['data' => ['deliver_to' => null]]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.create');
    }
}