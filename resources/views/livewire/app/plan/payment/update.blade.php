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
                <x-field label="Receipt Number" :value="$payment->number"/>
                <x-field label="Amount" :value="currency($payment->amount, $payment->currency)"/>
                <x-field label="Status" :badge="$payment->status"/>
            </div>
        </x-box>

        @if ($items = $payment->order->items)
            <x-box header="Order Summary">
                <div class="flex flex-col divide-y">
                    @foreach ($items as $item)
                        <x-field :label="$item->name">
                            <div class="text-right">
                                <div>{{ currency($item->grand_total, $item->currency) }}</div>

                                @if ($item->discount) 
                                    <div class="text-sm text-gray-500">
                                        {{ __('Discounted') }} {{ currency($item->discount, $item->currency) }}
                                    </div>
                                @endif
                            </div>
                        </x-field>
                    @endforeach
                </div>
            </x-box>
        @endif

        @if ($payment->amount > 0)
            <x-box header="Payment Details">
                <div class="flex flex-col divide-y">
                    <x-field label="Provider" :value="str()->headline($payment->provider)"/>

                    @if ($payment->is_auto_billing)
                        <x-field label="Auto Billing" value="Yes"/>
                    @endif

                    @tier('root')
                        @if ($payment->provider === 'stripe')
                            @if ($customerId = data_get($payment->data, 'metadata.stripe_customer_id'))
                                <x-field label="Stripe Customer ID" :value="$customerId"/>
                            @endif
                            @if ($subscriptionId = data_get($payment->data, 'metadata.stripe_subscription_id'))
                                <x-field label="Stripe Customer ID" :value="$subscriptionId"/>
                            @endif
                        @endif
                    @endtier
                </div>
            </x-box>
        @endif

        @tier('root')
            @if ($res = data_get($payment->data, 'pay_response'))
                <x-box header="Payment Response">
                    <div class="p-4 bg-slate-100 overflow-auto">
                        <pre x-text="JSON.stringify(@js($res), null, 4)"></pre>
                    </div>
                </x-box>
            @endif
        @endtier
    </div>
</div>