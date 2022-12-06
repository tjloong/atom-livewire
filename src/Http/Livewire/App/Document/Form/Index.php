<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;

    public $items;
    public $document;

    protected $listeners = [
        'setItems',
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
        $messages = [
            'document.type.required' => __('Unknown document type.'),
            'document.postfix.required' => __('Document number is required.'),
            'document.contact_id.required' => __('Please select a contact.'),
        ];

        return $messages;
    }

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->document->exists) {
            $this->setCurrency();
            $this->setContactInfo();
        }
    }

    /**
     * Get settings property
     */
    public function getSettingsProperty()
    {
        return account_settings($this->document->type);
    }

    /**
     * Get contact type property
     */
    public function getContactTypeProperty()
    {
        return in_array($this->document->type, ['purchase-order', 'bill']) ? 'vendor' : 'client';
    }

    /**
     * Get contact label property
     */
    public function getContactLabelProperty()
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
     * Get currencies property
     */
    public function getCurrenciesProperty()
    {
        $isBelongsToAccount = model('document')->enabledBelongsToAccountTrait;
        $currencies = $isBelongsToAccount ? account_settings('currencies') : site_settings('currencies');
        $defaultCurrency = $isBelongsToAccount ? account_settings('default_currency') : site_settings('default_currency');

        if ($currencies) return $currencies;
        else if ($defaultCurrency) return ['currency' => $defaultCurrency, 'rate' => 1];

        return [];
    }

    /**
     * Get totals property
     */
    public function getTotalsProperty()
    {
        if (!$this->items) return;

        $taxes = collect($this->items)->pluck('taxes')->collapse()->unique('id')
            ->map('collect')
            ->map(fn($tax) => $tax->put(
                'amount', 
                collect($this->items)->pluck('taxes')->collapse()
                    ->where('id', $tax->get('id'))
                    ->sum('amount'),
            ));

        $subtotal = collect($this->items)->sum('subtotal');
        $grandTotal = $subtotal + $taxes->sum('amount');

        return collect([['label' => 'Subtotal', 'amount' => $subtotal]])
            ->concat($taxes)
            ->concat([['label' => 'Grand Total', 'amount' => $grandTotal]]);
    }

    /**
     * Updated document contact id
     */
    public function updatedDocumentContactId()
    {
        $this->setContactInfo();
    }

    /**
     * Open currency modal
     */
    public function openCurrencyModal()
    {
        $this->emitTo(lw('app.document.form.currency-modal'), 'open', $this->document, $this->currencies);
    }

    /**
     * Set contact info
     */
    public function setContactInfo()
    {
        if ($contact = $this->document->contact) {
            $this->document->fill([
                'name' => $contact->name,
                'address' => format_address($contact),
                'payterm' => $this->document->type === 'delivery-order' 
                    ? null 
                    : data_get($contact, 'payterm'),
            ]);

            if ($currency = $contact->currency) $this->setCurrency($currency);
        }
        else {
            $this->document->fill([
                'name' => null,
                'address' => null,
                'person' => null,
                'payterm' => null,
                'contact_id' => null,
            ]);
        }
    }

    /**
     * Set currency
     */
    public function setCurrency($data = null)
    {
        if (!$data && $this->currencies) {
            $currency = collect($this->currencies)->first();
            $data = [
                'currency' => data_get($currency, 'currency'),
                'currency_rate' => data_get($currency, 'rate'),
            ];
        }

        if ($data) $this->document->fill($data);
    }

    /**
     * Set items
     */
    public function setItems($items)
    {
        $this->fill(['items' => $items]);
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

        foreach (($this->items ?? []) as $i => $input) {
            $data = array_merge(Arr::only($input, [
                'name', 'description', 'qty', 'amount', 'subtotal',
                'product_id', 'product_variant_id', 'sale_coa_id', 'purchase_coa_id',      
            ]), ['seq' => $i]);

            if ($item = $this->document->items()->find(data_get($input, 'id'))) {
                $item->fill($data)->save();
            }
            else {
                if (!data_get($data, 'product_id')) {
                    $product = $this->createProductFromDocumentItem($data);
                    $data = array_merge($data, ['product_id' => $product->id]);
                }

                $item = $this->document->items()->create($data);
            }

            if ($taxes = collect(data_get($input, 'taxes'))->mapWithKeys(fn($val) => [
                data_get($val, 'id') => ['amount' => data_get($val, 'amount')],
            ])) {
                $item->taxes()->sync($taxes);
            }
        }

        $this->document->sumTotal();
        $this->document->setSummary();
        $this->document->syncSplits();

        return redirect()->route('app.document.view', [$this->document->id]);
    }

    /**
     * Create product from document item
     */
    public function createProductFromDocumentItem($data)
    {
        $product = model('product')->create([
            'name' => data_get($data, 'name'),
            'type' => 'normal',
            'description' => data_get($data, 'description'),
            'is_active' => true,
            'sale_coa_id' => data_get($data, 'sale_coa_id'),
            'purchase_coa_id' => data_get($data, 'purchase_coa_id'),
        ]);

        if (in_array($this->document->type, ['purchase-order', 'bill'])) $prices = $product->costs();
        else $prices = $product->prices();

        $prices->create([
            'currency' => $this->document->currency,
            'amount' => data_get($data, 'amount'),
        ]);

        return $product;
    }

    /**
     * Get contacts
     */
    public function getContacts($search = null, $page = 1)
    {
        return model('contact')
            ->when(
                model('contact')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->where('type', $this->contactType)
            ->when($search, fn($q) => $q->filter(['search' => $search]))
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
     * Get convert from documents
     */
    public function getConvertFromDocuments($search = null, $page = 1, $sel = [])
    {
        return model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->where(fn($q) => $q
                ->when($search, fn($q) => $q->filter(['search' => $search]))
                ->orWhereIn('id', $sel)
                ->orWhere('type', [
                    'invoice' => 'quotation',
                    'bill' => 'purchase-order',
                    'delivery-order' => 'invoice',
                ][$this->document->type])            
            )
            ->when($this->document->contact_id, fn($q, $id) => $q->where('contact_id', $id))
            ->latest('issued_at')
            ->latest('id')
            ->toPage($page)
            ->through(fn($document) => [
                'value' => $document->id,
                'label' => $document->number,
                'small' => $document->name,
                'remark' => currency($document->splitted_total ?? $document->grand_total, $document->currency),
            ])
            ->toArray();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form');
    }
}