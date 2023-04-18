<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Body extends Component
{
    public $document;

    protected $listeners = ['refresh' => '$refresh'];

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
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.view.body');
    }
}