<?php

namespace Jiannius\Atom\Http\Livewire\Web\Shop;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Checkout extends Component
{
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
        return [
            'inputs.email' => ['required' => 'Email is required.'],
            'inputs.phone' => ['required' => 'Phone is required.'],
            'inputs.shipping_address.first_name' => ['nullable'],
            'inputs.shipping_address.last_name' => ['required' => 'Last name is required.'],
            'inputs.shipping_address.company' => ['nullable'],
            'inputs.shipping_address.address_1' => ['required' => 'Address line 1 is required.'],
            'inputs.shipping_address.address_2' => ['nullable'],
            'inputs.shipping_address.postcode' => ['required' => 'Postcode is required.'],
            'inputs.shipping_address.city' => ['required' => 'City is required.'],
            'inputs.shipping_address.state' => ['required' => 'State is required.'],
            'inputs.shipping_address.country' => ['required' => 'Country is required.'],
            'inputs.billing_address.same_as_shipping' => ['nullable'],
            'inputs.data.agree_email_marketing' => ['nullable'],
            'inputs.data.agree_phone_marketing' => ['nullable'],
            'inputs.shipping_rate_id' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->order = model('order')
            ->status('pending')
            ->where('ulid', session('shop_order'))
            ->when(user(), fn($q) => $q->where('user_id', user('id')))
            ->first();

        $this->items = optional($this->order)->items;

        if (optional($this->items)->count()) {
            $this->step = request()->query('step', 'info');

            $this->fill([
                'inputs.email' => $this->order->email ?? user('email'),
                'inputs.phone' => $this->order->phone ?? user('profile.phone'),
                'inputs.shipping_address' => $this->order->shipping_address ?? user('profile.shipping_address') ?? ['country' => 'MY'],
                'inputs.billing_address' => $this->order->billing_address ?? user('profile.billing_address') ?? ['same_as_shipping' => true],
                'inputs.data.agree_email_marketing' => true,
                'inputs.data.agree_phone_marketing' => false,
            ]);
        }
        else {
            return redirect()->route('web.shop.cart');
        }
    }

    /**
     * Gets shipping rates property
     */
    public function getRatesProperty()
    {
        if (collect($this->items)->where('is_required_shipping', true)->count()) {
            $weight = $this->items->sum('weight');
            $total = $this->items->sum('amount');
    
            return model('shipping_rate')
                ->where(fn($q) => $q
                    ->where(fn($q) => $q->weightCondition($weight))
                    ->orWhere(fn($q) => $q->amountCondition($total))
                )
                ->when(data_get($this->inputs, 'shipping_address.country'), fn($q, $country) => $q->country($country))
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
        if ($attr === 'shipping_address.country') $this->fill(['inputs.shipping_address.state' => null]);
        if ($attr === 'billing_address.country') $this->fill(['inputs.billing_address.state' => null]);
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