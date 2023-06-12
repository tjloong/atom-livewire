<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop;

use Jiannius\Atom\Traits\Livewire\WithCart;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Checkout extends Component
{
    use WithCart;
    use WithForm;

    public $step;
    public $order;
    public $items;
    public $inputs;
    public $payment;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return array_merge(
            [
                'inputs.customer.email' => ['required' => 'Email is required.'],
                'inputs.customer.phone' => ['required' => 'Phone is required.'],
                'inputs.shipping.first_name' => ['nullable'],
                'inputs.shipping.last_name' => ['required' => 'Last name is required.'],
                'inputs.shipping.company' => ['nullable'],
                'inputs.shipping.address_1' => ['required' => 'Address line 1 is required.'],
                'inputs.shipping.address_2' => ['nullable'],
                'inputs.shipping.postcode' => ['required' => 'Postcode is required.'],
                'inputs.shipping.city' => ['required' => 'City is required.'],
                'inputs.shipping.state' => ['required' => 'State is required.'],
                'inputs.shipping.country' => ['required' => 'Country is required.'],
                'inputs.data.agree_email_marketing' => ['nullable'],
                'inputs.data.agree_phone_marketing' => ['nullable'],
                'inputs.shipping_rate_id' => ['nullable'],
            ],

            data_get($this->inputs, 'billing.same_as_shipping') ? [] : [
                'inputs.billing.same_as_shipping' => ['nullable'],
                'inputs.billing.first_name' => ['nullable'],
                'inputs.billing.last_name' => ['required' => 'Last name is required.'],
                'inputs.billing.company' => ['nullable'],
                'inputs.billing.address_1' => ['required' => 'Address line 1 is required.'],
                'inputs.billing.address_2' => ['nullable'],
                'inputs.billing.postcode' => ['required' => 'Postcode is required.'],
                'inputs.billing.city' => ['required' => 'City is required.'],
                'inputs.billing.state' => ['required' => 'State is required.'],
                'inputs.billing.country' => ['required' => 'Country is required.'],
            ],
        );
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->loadOrder();

        if ($this->order) {
            $this->items = $this->order->items;

            if ($this->items->count()) {
                $this->step = request()->query('step', 'info');
    
                $this->fill([
                    'inputs.customer' => $this->order->customer ?? [
                        'email' => user('email'),
                        'phone' => user('profile.phone'),
                    ],
                    'inputs.shipping' => $this->order->shipping 
                        ?? user('profile.shipping') 
                        ?? ['country' => 'MY'],
    
                    'inputs.billing' => $this->order->billing 
                        ?? user('profile.billing') 
                        ?? ['same_as_shipping' => true],
    
                    'inputs.data.agree_email_marketing' => true,
                    'inputs.data.agree_phone_marketing' => false,
                ]);
            }
        }
    }

    /**
     * Gets shipping rates property
     */
    public function getRatesProperty()
    {
        if (collect($this->items)->where('data.is_required_shipping', true)->count()) {
            $weight = $this->items->sum('weight');
            $total = $this->items->sum('amount');
    
            return model('shipping_rate')
                ->where(fn($q) => $q
                    ->where(fn($q) => $q->weightCondition($weight))
                    ->orWhere(fn($q) => $q->amountCondition($total))
                )
                ->when(data_get($this->inputs, 'shipping.country'), fn($q, $country) => $q->country($country))
                ->status('active')
                ->orderBy('price')
                ->get();
        }

        return collect();
    }

    /**
     * Update inputs country
     */
    public function updatedInputs($val, $attr)
    {
        if ($attr === 'shipping.country') $this->fill(['inputs.shipping.state' => null]);
        if ($attr === 'billing.country') $this->fill(['inputs.billing.state' => null]);

        if ($attr === 'billing.same_as_shipping') {
            if ($val) $this->fill(['inputs.billing' => ['same_as_shipping' => true]]);
            else $this->fill([
                'inputs.billing.first_name' => null,
                'inputs.billing.last_name' => null,
                'inputs.billing.company' => null,
                'inputs.billing.address_1' => null,
                'inputs.billing.address_2' => null,
                'inputs.billing.postcode' => null,
                'inputs.billing.city' => null,
                'inputs.billing.state' => null,
                'inputs.billing.country' => data_get($this->inputs, 'shipping.country'),
            ]);
        }
    }

    /**
     * Set shipping rate
     */
    public function setShippingRate($id)
    {
        $this->order->fill(['shipping_rate_id' => $id])->save();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->validateForm();

        $this->order->fill($this->inputs)->save();

        $this->order->items->each(fn($item) => $item->setTaxes(
            data_get($this->inputs, 'billing.country') ?? data_get($this->inputs, 'shipping.country'),
            data_get($this->inputs, 'billing.state') ?? data_get($this->inputs, 'shipping.state'),
        ));

        $this->order->touch();

        if ($this->step === 'info' && $this->rates->count()) {
            if (!$this->order->shipping_rate_id) {
                $this->setShippingRate($this->rates->first()->id);
            }

            return redirect()->route('web.shop.checkout', ['step' => 'shipping']);
        }
        elseif ($this->step === 'shipping' && !$this->order->shipping_rate_id) {
            return $this->addError('shipping', 'Please select a shipping method.');
        }
        else {
            $this->payment = $this->order->payments()->create([
                'amount' => $this->order->grand_total,
            ]);

            if (str($this->order->number)->is('TEMP-*')) {
                $this->order->fill(['number' => null])->save(); // set a proper order number
            }
        }

        return $this->order->grand_total > 0
            ? $this->checkout()
            : $this->provision();
    }

    /**
     * Checkout
     */
    public function checkout()
    {
        $this->provision();

        // $payment = $order->payments()->create([
        //     'amount' => $this->order->grand_total,
        //     'mode' => 'stripe',
        //     'status' => 'draft',
        // ]);

        // $req = [
        //     'payment_id' => $payment->id,
        //     'payment_description' => 'Payment #'.$this->payment->number,
        // ];
    }

    /**
     * Provision
     */
    public function provision()
    {
        $job = collect([
            'App\Jobs\Shop\OrderProvision',
            'Jiannius\Atom\Jobs\Shop\OrderProvision',
        ])->first(fn($ns) => file_exists(atom_ns_path($ns)));

        return ($job)::dispatchSync([
            'metadata' => [
                'status' => 'success',
                'payment_id' => $this->payment->id,
            ],
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.shop.checkout');
    }
}