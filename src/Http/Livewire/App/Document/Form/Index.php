<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $items;
    public $columns;
    public $document;
    public $settings;

    public $inputs = [
        'labels' => [],
    ];

    public $filters = [
        'contact' => null,
        'source' => null,
    ];

    protected $listeners = [
        'setDocument',
        'setItem',
        'removeItem',
        'setCurrency', 
    ];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return array_merge(
            [
                'document.type' => ['required' => 'Unknown document type.'],
                'document.prefix' => ['nullable'],
                'document.postfix' => ['required' => 'Document number is required.'],
                'document.name' => ['nullable'],
                'document.address' => ['nullable'],
                'document.person' => ['nullable'],
                'document.reference' => ['nullable'],
                'document.payterm' => ['nullable'],
                'document.currency' => ['nullable'],
                'document.currency_rate' => ['nullable'],
                'document.description' => ['nullable'],
                'document.summary' => ['nullable'],
                'document.footer' => ['nullable'],
                'document.note' => ['nullable'],
                'document.contact_id' => ['required' => 'Please select a contact.'], 
                'document.converted_from_id' => ['nullable'],
                'document.issued_at' => ['nullable'],
                'document.delivered_at' => ['nullable'],
                'document.owned_by' => ['nullable'],
                'document.data' => ['nullable'],
            ],

            $this->document->type === 'quotation' ? [
                'document.data.valid_for' => ['nullable']
            ] : [],

            $this->document->type === 'delivery-order' ? [
                'document.data.delivery_channel' => ['nullable']
            ] : [],

            $this->document->type === 'purchase-order' ? [
                'document.data.deliver_to' => ['nullable']
            ] : [],

            [
                'inputs.labels' => ['nullable'],
            ],
        );
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->columns = $this->document->getColumns();

        $this->items = $this->document->items->map(function($item) {
            return enabled_module('taxes') 
                ? array_merge(
                    $item->toArray(), 
                    ['taxes' => $item->taxes->map(fn($tax) => [
                        'id' => $tax->id,
                        'label' => $tax->label,
                        'amount' => $tax->pivot->amount,
                    ])->toArray()]
                )
                : $item;
        })->toArray();

        $this->settings = tenant()
            ? tenant('settings.'.$this->document->type)
            : settings('document.'.$this->document->type);

        $this->fill([
            'inputs.labels' => $this->document->labels->pluck('id')->toArray(),
        ]);
    }

    /**
     * Get contact label property
     */
    public function getContactLabelProperty(): string
    {
        return [
            'quotation' => 'Client',
            'sales-order' => 'Client',
            'invoice' => 'Client',
            'delivery-order' => 'Deliver To',
            'purchase-order' => 'Vendor',
            'bill' => 'Vendor',
        ][$this->document->type];
    }

    /**
     * Get source label property
     */
    public function getSourceLabelProperty(): string
    {
        return [
            'invoice' => 'Quotation',
            'bill' => 'Purchase Order',
            'delivery-order' => 'Invoice',
        ][$this->document->type];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'contacts' => model('contact')->readable()
                ->where(fn($q) => $q
                    ->when($this->document->contact_id, fn($q, $id) => $q->whereIn('id', [$id]))
                    ->orWhere(fn($q) => $q->filter([
                        'category' => in_array($this->document->type, ['purchase-order', 'bill']) ? 'vendor' : 'client',
                        'search' => data_get($this->filters, 'contact'),
                    ]))
                )
                ->orderBy('name')
                ->take(100)
                ->get()
                ->transform(fn($contact) => [
                    'avatar' => optional($contact->logo)->url,
                    'avatar_placeholder' => $contact->name,
                    'value' => $contact->id,
                    'label' => $contact->name,
                    'small' => $contact->email,
                ])
                ->toArray(),

            'addresses' => $this->document->contact
                ? collect($this->document->contact->addresses)
                    ->map(fn($val) => format_address($val))
                    ->toArray()
                : [],

            'delivery_channels' => $this->document->type === 'delivery-order'
                ? data_get($this->settings, 'delivery_order.channels', [])
                : [],
            
            'sources' => in_array($this->document->type, ['invoice', 'bill', 'delivery-order'])
                ? model('document')->readable()
                    ->where(fn($q) => $q
                        ->when($this->document->converted_from_id, fn($q, $id) => $q->whereIn('id', [$id]))
                        ->orWhere('type', [
                            'invoice' => 'quotation',
                            'bill' => 'purchase-order',
                            'delivery-order' => 'invoice',
                        ][$this->document->type])
                        ->orWhere(fn($q) => $q->filter([
                            'search' => data_get($this->filters, 'source'),
                            'contact_id' => $this->document->contact_id,
                        ]))
                    )
                    ->latest('issued_at')
                    ->latest('id')
                    ->take(100)
                    ->get()
                    ->transform(fn($document) => [
                        'value' => $document->id,
                        'label' => $document->number,
                        'small' => $document->name,
                        'remark' => currency($document->splitted_total ?? $document->grand_total, $document->currency),
                    ])
                    ->toArray()
                : [],
        ];
    }

    /**
     * Updated document contact id
     */
    public function updatedDocumentContactId()
    {
        $this->document->fill(array_merge(
            [
                'name' => null,
                'address' => null,
                'person' => null,
                'payterm' => null,
            ],

            ($contact = $this->document->contact) ? [
                'name' => $contact->name,
                'address' => format_address($contact->addresses ? $contact->addresses->first() : $contact),
                'person' => $contact->persons->count() ? $contact->persons->first()->name : null,
                'payterm' => $this->document->type === 'delivery-order' ? null : $contact->payterm,
            ] : [],
        ));
    }

    /**
     * Set document
     */
    public function setDocument($data): void
    {
        $this->document->fill($data);
    }

    /**
     * Add item
     */
    public function addItem(): void
    {
        $this->items = collect($this->items)->push([
            'ulid' => (string) str()->ulid(),
            'name' => null,
            'description' => null,
            'qty' => 1,
            'amount' => null,
        ])->toArray();
    }

    /**
     * Remove item
     */
    public function removeItem($id): void
    {
        $this->items = collect($this->items)->reject(
            fn($item) => data_get($item, 'id') === $id
                || data_get($item, 'ulid') === $id
        )->values()->toArray();
    }

    /**
     * Sort items
     */
    public function sortItems($data): void
    {
        $this->items = collect($data)
            ->map(fn($id) => 
                collect($this->items)->firstWhere('id', $id)
                ?? collect($this->items)->firstWhere('ulid', $id)
            )
            ->values()
            ->toArray();
    }

    /**
     * Set item
     */
    public function setItem($input): void
    {
        $this->items = collect($this->items)
            ->map(fn($item) => 
                data_get($input, 'id') && data_get($item, 'id') === data_get($input, 'id')
                || data_get($input, 'ulid') && data_get($item, 'ulid') === data_get($input, 'ulid')
                    ? $input
                    : $item
            )
            ->values()
            ->toArray();

        $this->emitTo(atom_lw('app.document.form.total'), 'setItems', $this->items);
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->document->save();
        $this->document->labels()->sync(data_get($this->inputs, 'labels'));

        // items
        $ids = collect($this->items)->pluck('id')->values()->all();
        if ($ids) $this->document->items()->whereNotIn('id', $ids)->delete();

        foreach (($this->items ?? []) as $input) {
            $this->updateOrCreateDocumentItem($input);
        }

        $this->document->sumTotal();
        $this->document->setSummary();
        $this->document->syncSplits();

        return redirect()->route('app.document.view', [$this->document->id]);
    }

    /**
     * Update or create document item
     */
    public function updateOrCreateDocumentItem($input): void
    {
        $data = Arr::only($input, [
            'id',
            'name', 
            'description', 
            'qty', 
            'amount', 
            'subtotal',
            'product_id', 
            'product_variant_id',
        ]);

        if ($item = $this->document->items()->find(data_get($data, 'id'))) $item->fill($data)->save();
        else $item = $this->document->items()->create($data);

        $this->syncDocumentItemTaxes($item, $input);
    }

    /**
     * Sync document item taxes
     */
    public function syncDocumentItemTaxes($item, $input): void
    {
        if (enabled_module('taxes') && (
            $taxes = collect(data_get($input, 'taxes'))->mapWithKeys(fn($val) => [
                data_get($val, 'id') => ['amount' => data_get($val, 'amount')],
            ])
        )) {
            $item->taxes()->sync($taxes);
        }
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.form');
    }
}