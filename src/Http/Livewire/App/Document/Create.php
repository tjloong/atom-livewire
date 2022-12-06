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
    public function mount()
    {
        $this->authorize($this->type.'.create');

        $this->document = model('document')->fill([
            'type' => $this->type,
            'owned_by' => auth()->user()->id,
        ]);

        $this->document->setPrefixAndPostfix();
        
        $this->setConvertFrom();
        $this->setContact();
        $this->setExtraData();

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return str()->headline('Create '.$this->type);
    }

    /**
     * Set convert from
     */
    public function setConvertFrom()
    {
        if (!request()->query('convertFrom')) return;

        if (
            $src = model('document')->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )->find(request()->query('convertFrom'))
        ) {
            if ($this->document->type === 'invoice' && !in_array($src->type, ['quotation', 'sales-order'])) return;
            if ($this->document->type === 'bill' && $src->type !== 'purchase-order') return;
            if ($this->document->type === 'delivery-order' && $src->type !== 'invoice') return;
    
            $this->document->fill(['converted_from_id' => $src->id]);
        }
    }

    /**
     * Set contact
     */
    public function setContact()
    {
        if (
            $contact = optional($this->document->convertedFrom)->contact
                ?? model('contact')->when(
                    model('contact')->enabledBelongsToAccountTrait,
                    fn($q) => $q->belongsToAccount(),
                )->find(request()->query('contact'))
        ) {
            $this->document->fill(['contact_id' => $contact->id]);
        }
    }

    /**
     * Set extra data
     */
    public function setExtraData()
    {
        if ($this->type === 'quotation') $this->document->fill(['data' => ['valid_for' => 14]]);
        elseif ($this->type === 'delivery-order') $this->document->fill(['data' => ['delivery_channel' => null]]);
        elseif ($this->type === 'purchase-order') $this->document->fill(['data' => ['deliver_to' => null]]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.create');
    }
}