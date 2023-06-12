<div class="max-w-screen-md mx-auto flex flex-col items-center justify-center gap-10">
    <div class="flex items-center gap-4">
        @if ($payment->status === 'success') <x-icon name="circle-check" size="40" class="text-green-500"/>
        @else <x-icon name="circle-xmark" size="40" class="text-red-500"/>
        @endif

        <div class="text-4xl font-bold">
            {{ __(
                $payment->status === 'success'
                    ? 'Payment Success'
                    : 'Payment Failed'
            ) }}
        </div>
    </div>

    @if ($order = $payment->order)
        <div class="flex flex-col gap-4">
            <x-box header="Order Information">
                <div class="flex flex-col divide-y">
                    @foreach ([
                        'Number' => $order->number,
                        'Email' => data_get($order->customer, 'email'),
                        'Phone' => data_get($order->customer, 'phone'),
                        'Shipping Address' => format_address($order->shipping),
                        'Billing Address' => data_get($order->billing, 'same_as_shipping')
                            ? __('Same as shipping address')
                            : format_address($order->billing),
                    ] as $key => $val)
                        <x-field :label="$key" :value="$val"/>
                    @endforeach
                </div>
            </x-box>
    
            <x-box header="Items">
                <div class="flex flex-col divide-y">
                    @livewire(atom_lw('web.shop.cart.item'), [
                        'readonly' => true,
                        'order' => $order,
                    ], key('item'))

                    @livewire(atom_lw('web.shop.cart.sum'), compact('order'), key('sum'))
                </div>
            </x-box>
        </div>
    @endif

    <x-button inverted href="/" label="Back to Home" icon="arrow-left"/>
</div>