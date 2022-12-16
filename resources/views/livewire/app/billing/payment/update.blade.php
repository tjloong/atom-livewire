<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="'Payment #'.$payment->number" back>
        <x-dropdown>
            <x-slot:anchor>
                <x-button color="gray" label="Download"/>
            </x-slot:anchor>

                @foreach (['invoice', 'receipt'] as $val)
                    <x-dropdown.item 
                        :label="str($val)->headline()"
                        wire:click="download('{{ $val }}')"
                    />
                @endforeach
        </x-dropdown>
    </x-page-header>

    <div class="flex flex-col gap-6">
        <x-box header="Payment Information">
            <div class="flex flex-col divide-y">
                <x-box.row label="Receipt Number">
                    {{ $payment->number }}
                </x-box.row>
    
                <x-box.row label="Amount">
                    {{ currency($payment->amount, $payment->currency) }}
                </x-box.row>
    
                <x-box.row label="Status">
                    <x-badge :label="$payment->status"/>
                </x-box.row>
            </div>
        </x-box>

        @if ($items = $payment->order->items)
            <x-box header="Order Summary">
                <div class="flex flex-col divide-y">
                    @foreach ($items as $item)
                        <x-box.row :label="$item->name">
                            <div class="text-right">
                                <div>{{ currency($item->grand_total, $item->currency) }}</div>

                                @if ($item->discount) 
                                    <div class="text-sm text-gray-500">
                                        {{ __('Discounted') }} {{ currency($item->discount, $item->currency) }}
                                    </div>
                                @endif
                            </div>
                        </x-box.row>
                    @endforeach
                </div>
            </x-box>
        @endif

        @if ($payment->amount > 0)
            <x-box header="Payment Details">
                <div class="flex flex-col divide-y">
                    <x-box.row label="Provider">
                        {{ str()->headline($payment->provider) }}
                    </x-box.row>

                    @if ($payment->is_auto_billing)
                        <x-box.row label="Auto Billing">{{ __('Yes') }}</x-box.row>
                    @endif

                    @accounttype('root')
                        @if ($payment->provider === 'stripe')
                            @if ($customerId = data_get($payment->data, 'metadata.stripe_customer_id'))
                                <x-box.row label="Stripe Customer ID">{{ $customerId }}</x-box.row>
                            @endif
                            @if ($subscriptionId = data_get($payment->data, 'metadata.stripe_subscription_id'))
                                <x-box.row label="Stripe Customer ID">{{ $subscriptionId }}</x-box.row>
                            @endif
                        @endif
                    @endaccounttype
                </div>
            </x-box>
        @endif

        @accounttype('root')
            @if ($res = data_get($payment->data, 'pay_response'))
                <x-box header="Payment Response">
                    <div class="p-4 bg-slate-100 overflow-auto">
                        <pre x-text="JSON.stringify(@js($res), null, 4)"></pre>
                    </div>
                </x-box>
            @endif
        @endaccounttype
    </div>
</div>