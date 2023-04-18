<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Traits\Livewire\WithShareable;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;
    use WithShareable;

    public $document;

    /**
     * Mount
     */
    public function mount($documentId): void
    {
        $this->document = model('document')->readable()->findOrFail($documentId);

        $this->authorize($this->document->type.'.view');

        $this->shareable = $this->document->shareable;

        breadcrumbs()->push('#'.$this->document->number);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        $prefix = [
            'delivery-order' => 'DO',
            'purchase-order' => 'PO',
            'sales-order' => 'SO',
            'invoice' => 'Invoice',
            'quotation' => 'Quoation',
            'bill' => 'Bill',
        ][$this->document->type] ?? null;

        return collect([$prefix, '#'.$this->document->number])->filter()->join(' ');
    }

    /**
     * Get info fields property
     */
    public function getInfoFieldsProperty(): array
    {
        return array_merge(
            [
                'Number' => $this->document->number,
                'Issued Date' => format_date($this->document->issued_at),
                'Status' => ['badge' => $this->document->status],
            ],
    
            ($validfor = data_get($this->document->data, 'valid_for')) ? [
                'Valid For' => __(':valid '.str('day')->plural($validfor), ['valid' => $validfor]),
            ] : [],
    
            ($ref = $this->document->reference) ? [
                'Reference' => $ref,
            ] : [],
    
            ($src = $this->document->convertedFrom) ? [
                'Convert From' => [
                    'value' => $src->type.' #'.$src->number,
                    'href' => route('app.document.view', [$src->id]),
                ],
            ] : [],
    
            $this->document->type === 'delivery-order' ? [
                'Delivery Channel' => data_get($this->document->data, 'delivery_channel'),
                'Delivered Date' => format_date($this->document->delivered_at) ?? '--',
            ] : [],
    
            ($payterm = $this->document->formatted_payterm) ? [
                'Payment Term' => $payterm,
            ] : [],
    
            ($desc = $this->document->description) ? [
                'Description' => $desc,
            ] : [],
        );
    }

    /**
     * Get additional info fields property
     */
    public function getAdditionalInfoFieldsProperty(): array
    {
        return array_merge(
            [
                'Owner' => $this->document->ownedBy->name,
                'Created Date' => format_date($this->document->created_at),
            ],

            $this->document->last_sent_at ? [
                'Last Sent' => format_date($this->document->last_sent_at),
            ] : [],

            $this->document->labels->count() ? [
                'Labels' => ['tags' => $this->document->labels->map(fn($label) => $label->locale('name'))->toArray()],
            ] : [],
        );
    }

    /**
     * Get items property
     */
    public function getItemsProperty(): mixed
    {
        if ($master = $this->document->splittedFrom) return $master->items;
        
        return $this->document->items;
    }

    /**
     * Get columns property
     */
    public function getColumnsProperty()
    {
        return $this->document->getColumns();
    }

    /**
     * Toggle sent
     */
    public function toggleSent()
    {
        $this->document->fill([
            'last_sent_at' => $this->document->last_sent_at
                ? null
                : now(),
        ])->saveQuietly();

        $this->emit('refresh');
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->document->delete();

        return breadcrumbs()->back();
    }

    /**
     * PDF
     */
    public function pdf(): mixed
    {
        return $this->document->pdf();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.view');
    }
}