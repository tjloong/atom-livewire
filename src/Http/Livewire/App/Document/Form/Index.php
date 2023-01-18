<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;

    public $items;
    public $columns;
    public $document;
    public $settings;

    protected $listeners = [
        'setDocument',
        'setItem',
        'removeItem',
        'setCurrency', 
    ];

    /**
     * Validation rules
     */
    public function rules()
    {
        return array_merge(
            [
                'document.type' => 'required',
                'document.prefix' => 'nullable',
                'document.postfix' => 'required',
                'document.name' => 'nullable',
                'document.address' => 'nullable',
                'document.person' => 'nullable',
                'document.reference' => 'nullable',
                'document.payterm' => 'nullable',
                'document.currency' => 'nullable',
                'document.currency_rate' => 'nullable',
                'document.description' => 'nullable',
                'document.summary' => 'nullable',
                'document.footer' => 'nullable',
                'document.note' => 'nullable',
                'document.contact_id' => 'required', 
                'document.converted_from_id' => 'nullable',
                'document.issued_at' => 'nullable',
                'document.delivered_at' => 'nullable',
                'document.owned_by' => 'nullable',
                'document.data' => 'nullable',
            ],

            $this->document->type === 'quotation'
                ? ['document.data.valid_for' => 'nullable']
                : [],

            $this->document->type === 'delivery-order'
                ? ['document.data.delivery_channel' => 'nullable']
                : [],

            $this->document->type === 'purchase-order'
                ? ['document.data.deliver_to' => 'nullable']
                : [],
        );
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'document.type.required' => __('Unknown document type.'),
            'document.postfix.required' => __('Document number is required.'),
            'document.contact_id.required' => __('Please select a contact.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->columns = $this->document->getColumns();

        $this->items = $this->document->items()
            ->with('taxes')
            ->get()
            ->transform(fn($item) => array_merge($item->toArray(), [
                'taxes' => $item->taxes->map(fn($tax) => [
                    'id' => $tax->id,
                    'label' => $tax->label,
                    'amount' => $tax->pivot->amount,
                ])->toArray(),
            ]))
            ->toArray();

        $this->settings = model('document')->enabledBelongsToAccountTrait
            ? account_settings($this->document->type)
            : site_settings('app.document.'.$this->document->type);
    }

    /**
     * Set document
     */
    public function setDocument($data)
    {
        $this->document->fill($data);
    }

    /**
     * Add item
     */
    public function addItem()
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
    public function removeItem($id)
    {
        $this->items = collect($this->items)->reject(
            fn($item) => data_get($item, 'id') === $id
                || data_get($item, 'ulid') === $id
        )->values()->toArray();
    }

    /**
     * Sort items
     */
    public function sortItems($data)
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
    public function setItem($input)
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

        $this->emitTo(lw('app.document.form.total'), 'setItems', $this->items);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->document->save();

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
    public function updateOrCreateDocumentItem($input)
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
    public function syncDocumentItemTaxes($item, $input)
    {
        if ($taxes = collect(data_get($input, 'taxes'))->mapWithKeys(fn($val) => [
            data_get($val, 'id') => ['amount' => data_get($val, 'amount')],
        ])) {
            $item->taxes()->sync($taxes);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form');
    }
}